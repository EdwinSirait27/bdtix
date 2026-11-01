<?php

namespace Tests\Feature;

use App\Helpers\StorageHelper;
use App\Jobs\UploadAttachmentToS3;
use App\Jobs\UploadAttachmentToS3ForExecutor;
use App\Models\User;
use App\Services\VirusScanner;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Spatie\Permission\Middleware\RoleMiddleware;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    private const OWNER = 'owner-id';
    private const TICKET = 'ticket-id';
    private const USER_FILE = 'user-file-id';
    private const EXECUTOR_FILE = 'executor-file-id';

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections.mysql' => config('database.connections.sqlite')]);
        DB::purge('mysql');
        $this->withoutMiddleware(RoleMiddleware::class);
        $this->withoutVite();
    }

    private function seedAttachments(string $mime = 'image/png', string $fileName = 'a.png'): void
    {
        Schema::connection('mysql')->create('ticket_tables', function (Blueprint $t) {
            $t->string('id')->primary();
            $t->string('user_id');
            $t->softDeletes();
        });

        foreach (['ticket_attachment', 'ticket_executor_attachments'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->string('id')->primary();
                $t->string('ticket_id');
                $t->string('user_id')->nullable();
                $t->string('executor_id')->nullable();
                $t->string('file_name');
                $t->string('file_path');
                $t->string('original_name')->nullable();
                $t->string('mime_type')->nullable();
                $t->string('status');
                $t->softDeletes();
                $t->timestamps();
            });
        }

        DB::connection('mysql')->table('ticket_tables')->insert(['id' => self::TICKET, 'user_id' => self::OWNER]);

        $row = [
            'ticket_id' => self::TICKET,
            'file_name' => $fileName,
            'file_path' => "folder/{$fileName}",
            'original_name' => '<img src=x onerror=alert(1)>.png',
            'mime_type' => $mime,
            'status' => 'uploaded',
        ];

        DB::table('ticket_attachment')->insert($row + ['id' => self::USER_FILE, 'user_id' => self::OWNER]);
        DB::table('ticket_executor_attachments')->insert($row + ['id' => self::EXECUTOR_FILE, 'executor_id' => 'exec-id']);

        Storage::fake('s3');
        Storage::disk('s3')->put("folder/{$fileName}", 'content');
    }

    private function userWithRoles(string $id, array $roles): User
    {
        $user = new class extends User {
            public array $fakeRoles = [];

            public function hasAnyRole(...$roles): bool
            {
                return array_intersect($this->fakeRoles, collect($roles)->flatten()->all()) !== [];
            }
        };
        $user->forceFill(['id' => $id]);
        $user->fakeRoles = $roles;

        return $user;
    }

    public function test_login_rejects_array_input_instead_of_crashing(): void
    {
        $this->post('/login', ['username' => ['a', 'b'], 'password' => 'x'])
            ->assertRedirect()
            ->assertSessionHasErrors('username');
    }

    public function test_login_blocks_password_equal_to_username_in_any_case(): void
    {
        Auth::shouldReceive('attempt')->never();
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        foreach ([['budi', 'budi'], ['Budi', 'budi'], ['budi', 'BUDI ']] as [$username, $password]) {
            $this->post('/login', ['username' => $username, 'password' => $password])
                ->assertRedirect()
                ->assertSessionHas('error', fn ($m) => str_contains($m, 'Silakan ganti password terlebih dahulu'));
        }
    }

    public function test_password_equal_to_username_counts_toward_the_rate_limit(): void
    {
        Auth::shouldReceive('attempt')->never();
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', ['username' => 'budi', 'password' => 'budi']);
        }

        $response->assertSessionHas('error', fn ($m) => str_contains($m, 'Too many failed attempts'));
    }

    public function test_different_password_still_reaches_authentication(): void
    {
        Auth::shouldReceive('attempt')->once()->andReturn(false);
        Auth::shouldReceive('userResolver')->andReturn(fn () => null);

        $this->post('/login', ['username' => 'budi', 'password' => 'rahasia123'])
            ->assertSessionHas('error', 'Wrong username or password');
    }

    public function test_login_route_throttles_requests_per_minute(): void
    {
        Auth::shouldReceive('attempt')->andReturn(false);
        Auth::shouldReceive('userResolver')->andReturn(fn () => null);

        for ($i = 0; $i < 15; $i++) {
            $this->post('/login', ['username' => "user{$i}", 'password' => 'rahasia123'])->assertRedirect();
        }

        $this->postJson('/login', ['username' => 'user99', 'password' => 'rahasia123'])->assertStatus(429);
    }

    public function test_login_locks_the_username_from_a_rotating_ip(): void
    {
        Auth::shouldReceive('attempt')->times(20)->andReturn(false);
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        for ($i = 0; $i < 25; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => "10.0.0.{$i}"])
                ->post('/login', ['username' => 'Victim', 'password' => "guess{$i}"]);
        }

        $response->assertSessionHas('error', fn ($m) => str_contains($m, 'Too many failed attempts'));
    }

    public function test_login_locks_an_ip_trying_many_usernames(): void
    {
        Auth::shouldReceive('attempt')->times(50)->andReturn(false);
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        for ($i = 0; $i < 55; $i++) {
            $response = $this->post('/login', ['username' => "user{$i}", 'password' => 'Password1']);
        }

        $response->assertSessionHas('error', fn ($m) => str_contains($m, 'Too many failed attempts'));
    }

    public function test_language_switch_rejects_unknown_locale_and_external_referer(): void
    {
        $this->get('/lang/xx')->assertNotFound();

        $this->withHeader('referer', 'https://evil.example/phish')
            ->get('/lang/en')
            ->assertRedirect(route('login'));
    }

    public function test_responses_carry_security_headers(): void
    {
        $this->get('/')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_guest_cannot_read_attachment(): void
    {
        $this->seedAttachments();

        $this->get('/attachments/' . self::USER_FILE . '/file')->assertRedirect(route('login'));
        $this->get('/attachmentsforexecutor/' . self::EXECUTOR_FILE . '/file')->assertRedirect(route('login'));
    }

    public function test_other_human_user_cannot_read_attachments(): void
    {
        $this->seedAttachments();
        $this->actingAs($this->userWithRoles('other', ['human']));

        $this->getJson('/attachments/' . self::USER_FILE . '/file')->assertForbidden();
        $this->getJson('/attachments/' . self::USER_FILE . '/signed-url')->assertForbidden();
        $this->getJson('/attachmentsforexecutor/' . self::EXECUTOR_FILE . '/file')->assertForbidden();
        $this->getJson('/attachmentsforexecutor/' . self::EXECUTOR_FILE . '/signed-url-for-executor')->assertForbidden();
    }

    public function test_owner_and_staff_get_a_same_origin_url_and_the_file(): void
    {
        $this->seedAttachments();

        foreach ([[self::OWNER, ['human']], ['staff', ['executor']], ['boss', ['admin']]] as [$id, $roles]) {
            $this->actingAs($this->userWithRoles($id, $roles));

            $this->getJson('/attachments/' . self::USER_FILE . '/signed-url')
                ->assertOk()
                ->assertJsonPath('url', '/attachments/' . self::USER_FILE . '/file');

            $this->get('/attachmentsforexecutor/' . self::EXECUTOR_FILE . '/file')
                ->assertOk()
                ->assertHeader('Content-Type', 'image/png');
        }
    }

    public function test_active_content_is_forced_to_download(): void
    {
        $this->seedAttachments('text/html', 'evil.html');
        $this->actingAs($this->userWithRoles(self::OWNER, ['human']));

        $response = $this->get('/attachments/' . self::USER_FILE . '/file')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/octet-stream')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->assertStringStartsWith('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_stored_name_ignores_dangerous_client_extension_and_is_unique(): void
    {
        $file = UploadedFile::fake()->createWithContent('evil.html', 'just text <script>alert(1)</script>');

        $name = StorageHelper::attachmentFileName('prefix', $file);

        $this->assertMatchesRegularExpression('/^prefix-[a-z0-9]{12}\.(txt|bin)$/', $name);
        $this->assertNotSame($name, StorageHelper::attachmentFileName('prefix', $file));
        $this->assertStringEndsWith('.docx', StorageHelper::attachmentFileName('p', UploadedFile::fake()->create('a.DOCX', 1)));
    }

    private function fakeScanner(?string $signature = null, bool $broken = false): VirusScanner
    {
        return new class($signature, $broken) extends VirusScanner {
            public function __construct(private ?string $signature, private bool $broken) {}

            public function scanLocalFile(string $path): ?string
            {
                if ($this->broken) {
                    throw new RuntimeException('Cannot connect to ClamAV');
                }

                return $this->signature;
            }
        };
    }

    public static function uploadJobs(): array
    {
        return [
            'user' => [UploadAttachmentToS3::class, 'ticket_attachment', self::USER_FILE],
            'executor' => [UploadAttachmentToS3ForExecutor::class, 'ticket_executor_attachments', self::EXECUTOR_FILE],
        ];
    }

    private function prepareUpload(string $table, string $id): void
    {
        $this->seedAttachments();
        Storage::disk('s3')->delete('folder/a.png');
        Storage::fake('local');
        Storage::disk('local')->put('temp/a.png', 'content');
        DB::table($table)->where('id', $id)->update(['status' => 'pending']);
    }

    #[DataProvider('uploadJobs')]
    public function test_clean_file_is_uploaded(string $job, string $table, string $id): void
    {
        $this->prepareUpload($table, $id);

        (new $job($id, 'temp/a.png', 'folder', 'a.png'))->handle($this->fakeScanner());

        Storage::disk('s3')->assertExists('folder/a.png');
        Storage::disk('local')->assertMissing('temp/a.png');
        $this->assertSame('uploaded', DB::table($table)->where('id', $id)->value('status'));
    }

    #[DataProvider('uploadJobs')]
    public function test_infected_file_never_reaches_storage(string $job, string $table, string $id): void
    {
        $this->prepareUpload($table, $id);

        (new $job($id, 'temp/a.png', 'folder', 'a.png'))->handle($this->fakeScanner('Win.Test.EICAR'));

        Storage::disk('s3')->assertMissing('folder/a.png');
        Storage::disk('local')->assertMissing('temp/a.png');
        $this->assertSame('infected', DB::table($table)->where('id', $id)->value('status'));
    }

    #[DataProvider('uploadJobs')]
    public function test_scanner_outage_fails_closed_and_keeps_file_for_retry(string $job, string $table, string $id): void
    {
        $this->prepareUpload($table, $id);
        $instance = new $job($id, 'temp/a.png', 'folder', 'a.png');

        try {
            $instance->handle($this->fakeScanner(broken: true));
            $this->fail('Scanner outage must not be swallowed.');
        } catch (RuntimeException) {
        }

        Storage::disk('s3')->assertMissing('folder/a.png');
        Storage::disk('local')->assertExists('temp/a.png');
        $this->assertSame('failed', DB::table($table)->where('id', $id)->value('status'));

        $instance->failed(null);
        Storage::disk('local')->assertMissing('temp/a.png');
    }

    public function test_infected_attachment_cannot_be_opened(): void
    {
        $this->seedAttachments();
        DB::table('ticket_attachment')->where('id', self::USER_FILE)->update(['status' => 'infected']);
        $this->actingAs($this->userWithRoles('boss', ['admin']));

        $this->getJson('/attachments/' . self::USER_FILE . '/file')->assertNotFound();
        $this->getJson('/attachments/' . self::USER_FILE . '/signed-url')->assertNotFound();
    }
}
