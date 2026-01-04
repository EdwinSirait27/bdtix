<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tickets;
use App\Models\User;
use App\Services\NextcloudService;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Ticketattachments;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachmentsJob implements ShouldQueue
// {
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public $timeout = 300; // 5 menit
//     public $tries   = 3;

//     public function __construct(
//         public string $ticketId,
//         public array  $files,
//         public User   $user
//     ) {}

//     public function handle()
//     {
//         $ticket = Tickets::findOrFail($this->ticketId);

//         $category = Str::slug($ticket->category);
//         $userName = Str::slug($this->user->username);
//         $basePath = "ticket/{$category}/{$userName}/{$ticket->id}";

//         NextcloudService::makeDir($basePath);

//         foreach ($this->files as $file) {
//             $filename = time().'_'.$file->getClientOriginalName();

//             NextcloudService::upload(
//                 $basePath,
//                 $filename,
//                 file_get_contents($file->getRealPath()),
//                 $file->getMimeType()
//             );

//             Ticketattachments::create([
//                 'id'        => Str::uuid(),
//                 'ticket_id' => $ticket->id,
//                 'file_name' => $filename,
//                 'file_path' => "{$basePath}/{$filename}",
//             ]);
//         }

//         $shareUrl = NextcloudService::shareFolder($basePath);

//         $ticket->update([
//             'attachment_folder' => $basePath,
//             'attachment_url'    => $shareUrl,
//         ]);
//     }
// }
// {
//     use Dispatchable, Queueable, SerializesModels;

//     public function __construct(
//         public string $ticketId,
//         public array $files,
//         public string $userId
//     ) {}

//     public function handle()
//     {
//         $ticket = Tickets::findOrFail($this->ticketId);
//         $user   = User::findOrFail($this->userId);

//         $category = Str::slug($ticket->category);
//         $username = Str::slug($user->username);
//         $basePath = "ticket/{$category}/{$username}/{$ticket->id}";

//         NextcloudService::makeDir($basePath);

//         foreach ($this->files as $file) {
//             $content = Storage::get($file['path']);
//             $filename = time().'_'.$file['name'];

//             NextcloudService::upload(
//                 $basePath,
//                 $filename,
//                 $content,
//                 $file['mime']
//             );

//             Ticketattachments::create([
//                 'id'        => Str::uuid(),
//                 'ticket_id' => $ticket->id,
//                 'file_name' => $filename,
//                 'file_path' => "{$basePath}/{$filename}",
//             ]);

//             // 🔥 hapus file sementara
//             Storage::delete($file['path']);
//         }

//         $shareUrl = NextcloudService::shareFolder($basePath);

//         $ticket->update([
//             'attachment_folder' => $basePath,
//             'attachment_url'    => $shareUrl,
//         ]);
//     }
// }

// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public int $tries = 3;
//     public int $timeout = 120;

//     public function __construct(
//         public string $ticketId,
//         public array $files,
//         public string $userId
//     ) {}

//     public function handle()
//     {
//         Log::info('ATTACHMENT_JOB_START', [
//             'ticket_id' => $this->ticketId,
//             'files'     => count($this->files),
//         ]);

//         try {
//             $ticket = Tickets::findOrFail($this->ticketId);
//             $user   = User::findOrFail($this->userId);

//             $category = Str::slug($ticket->category);
//             $username = Str::slug($user->username);
//             $basePath = "ticket/{$category}/{$username}/{$ticket->id}";

//             NextcloudService::makeDir($basePath);

//             foreach ($this->files as $file) {

//                 if (!Storage::exists($file['path'])) {
//                     Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
//                         'path' => $file['path']
//                     ]);
//                     continue;
//                 }

//                 $content  = Storage::get($file['path']);
//                 $filename = time() . '_' . Str::slug($file['name']);

//                 NextcloudService::upload(
//                     $basePath,
//                     $filename,
//                     $content,
//                     $file['mime']
//                 );

//                 Ticketattachments::create([
//                     'id'        => (string) Str::uuid(),
//                     'ticket_id' => $ticket->id,
//                     'file_name' => $filename,
//                     'file_path' => "{$basePath}/{$filename}",
//                 ]);

//                 // 🔥 hapus file sementara (aman)
//                 Storage::delete($file['path']);
//             }

//             $shareUrl = NextcloudService::shareFolder($basePath);

//             $ticket->update([
//                 'attachment_folder' => $basePath,
//                 'attachment_url'    => $shareUrl,
//             ]);

//             Log::info('ATTACHMENT_JOB_DONE', [
//                 'ticket_id' => $ticket->id,
//                 'share_url' => $shareUrl,
//             ]);

//         } catch (\Throwable $e) {
//             Log::error('ATTACHMENT_JOB_FAILED', [
//                 'ticket_id' => $this->ticketId,
//                 'error'     => $e->getMessage(),
//             ]);

//             // ⛔ hapus file tmp kalau job benar-benar gagal
//             foreach ($this->files as $file) {
//                 if (isset($file['path']) && Storage::exists($file['path'])) {
//                     Storage::delete($file['path']);
//                 }
//             }

//             throw $e; // ⬅ WAJIB supaya retry & masuk failed_jobs
//         }
//     }
// }
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(
        public string $ticketId,
        public array  $files,
        public string $userId
    ) {}

    public function handle(): void
    {
        Log::info('ATTACHMENT_JOB_START', [
            'ticket_id' => $this->ticketId,
            'files'     => count($this->files),
        ]);

        // ✅ kalau tidak ada file → langsung selesai
        if (empty($this->files)) {
            Log::info('ATTACHMENT_JOB_SKIP_EMPTY', [
                'ticket_id' => $this->ticketId,
            ]);
            return;
        }

        try {
            $ticket = Tickets::findOrFail($this->ticketId);
            $user   = User::findOrFail($this->userId);

            $category = Str::slug($ticket->category);
            $username = Str::slug($user->username);
            $basePath = "ticket/{$category}/{$username}/{$ticket->id}";

            // 📁 pastikan folder ada
            NextcloudService::makeDir($basePath);

            foreach ($this->files as $file) {

                if (
                    empty($file['path']) ||
                    !Storage::exists($file['path'])
                ) {
                    Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
                        'ticket_id' => $ticket->id,
                        'path'      => $file['path'] ?? null,
                    ]);
                    continue;
                }

                // ⚠️ load file
                $content  = Storage::get($file['path']);
                $filename = time() . '_' . Str::slug($file['name']);

                // ☁️ upload ke Nextcloud
                NextcloudService::upload(
                    $basePath,
                    $filename,
                    $content,
                    $file['mime']
                );

                // 💾 simpan DB
                Ticketattachments::create([
                    'id'        => (string) Str::uuid(),
                    'ticket_id' => $ticket->id,
                    'file_name' => $filename,
                    'file_path' => "{$basePath}/{$filename}",
                ]);

                // 🧹 hapus tmp file
                Storage::delete($file['path']);
            }

            // 🔗 share folder
            $shareUrl = NextcloudService::shareFolder($basePath);

            $ticket->update([
                'attachment_folder' => $basePath,
                'attachment_url'    => $shareUrl,
            ]);

            Log::info('ATTACHMENT_JOB_DONE', [
                'ticket_id' => $ticket->id,
                'share_url' => $shareUrl,
            ]);

        } catch (\Throwable $e) {

            Log::error('ATTACHMENT_JOB_FAILED', [
                'ticket_id' => $this->ticketId,
                'error'     => $e->getMessage(),
            ]);

            // 🔥 cleanup tmp file (jaga disk)
            foreach ($this->files as $file) {
                if (
                    isset($file['path']) &&
                    Storage::exists($file['path'])
                ) {
                    Storage::delete($file['path']);
                }
            }

          
            throw $e;
        }
    }
}