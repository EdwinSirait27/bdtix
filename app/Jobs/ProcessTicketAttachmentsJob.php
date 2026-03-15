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
use Throwable;

class ProcessTicketAttachmentsJob implements ShouldQueue
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
        if (!config('services.nextcloud.enabled')) {
            Log::info('ATTACHMENT_JOB_SKIPPED_NEXTCLOUD_DISABLED', [
                'ticket_id' => $this->ticketId,
                'files'     => count($this->files),
            ]);
            foreach ($this->files as $file) {
                if (isset($file['path']) && Storage::exists($file['path'])) {
                    Storage::delete($file['path']);
                }
            }
            return;
        }

        Log::info('ATTACHMENT_JOB_START', [
            'ticket_id' => $this->ticketId,
            'files'     => count($this->files),
        ]);

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

              
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
// $extension    = pathinfo($file['name'], PATHINFO_EXTENSION);
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = time() . '_' . Str::slug($originalName) . '.' . $extension;
                NextcloudService::upload(
    $basePath,
    $filename,
    Storage::path($file['path']), 
    $file['mime']
);


                Ticketattachments::create([
                    'id'        => (string) Str::uuid(),
                    'ticket_id' => $ticket->id,
                    'file_name' => $filename,
                    'file_path' => "{$basePath}/{$filename}",
                ]);

                Storage::delete($file['path']);
            }

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
