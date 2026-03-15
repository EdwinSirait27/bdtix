<?php

namespace App\Jobs;

use App\Models\Ticketattachments;
use App\Models\TicketExecutorAttachment;
use App\Models\Tickets;
use App\Services\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessAttachmentToDriveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maksimal retry jika gagal
     */
    public int $tries = 3;

    /**
     * Timeout per job (detik)
     */
    public int $timeout = 180;

    /**
     * @param string $ticketId
     * @param array  $tempFiles  [['path' => ..., 'name' => ..., 'mime' => ...], ...]
     * @param string $nip
     * @param string $category
     * @param string $uploadedBy  user/executor ID
     * @param string $type        'user' atau 'executor'
     */
    public function __construct(
        public string $ticketId,
        public array  $tempFiles,
        public string $folderIdentity,
        public string $category,
        public string $uploadedBy,
        public string $type = 'user', // 'user' atau 'executor'
        public ?string $filePrefix = null,
    ) {}

    public function handle(GoogleDriveService $driveService): void
    {
        foreach ($this->tempFiles as $tempFile) {
            try {
                // Ambil file dari storage sementara
                $fullPath = Storage::path($tempFile['path']);

                if (!file_exists($fullPath)) {
                    Log::warning('Temp file not found, skipping', ['path' => $fullPath]);
                    continue;
                }

                // Buat UploadedFile dari path sementara
                $file = new \Illuminate\Http\UploadedFile(
                    $fullPath,
                    $tempFile['name'],
                    $tempFile['mime'],
                    null,
                    true // test mode — skip is_uploaded_file check
                );

                // Upload ke Google Drive
                $driveData = $driveService->uploadAttachment(
                    $file,
                    $this->folderIdentity,
                    $this->category,
                    $this->type,
                    $this->filePrefix
                );

                // Simpan ke database sesuai tipe
                if ($this->type === 'executor') {
                    TicketExecutorAttachment::create([
                        'id'               => (string) Str::uuid(),
                        'ticket_id'        => $this->ticketId,
                        'executor_id'      => $this->uploadedBy,
                        'file_name'        => $driveData['original_name'],
                        'file_path'        => $driveData['web_view_link'],
                        'original_name'    => $driveData['original_name'],
                        'mime_type'        => $driveData['mime_type'],
                        'size'             => $driveData['size'],
                        'drive_file_id'    => $driveData['drive_file_id'],
                        'drive_folder_id'  => $driveData['folder_id'] ?? null,
                        'web_view_link'    => $driveData['web_view_link'],
                        'web_content_link' => $driveData['web_content_link'],
                    ]);
                } else {
                    Ticketattachments::create([
                        'id'               => (string) Str::uuid(),
                        'ticket_id'        => $this->ticketId,
                        'user_id'          => $this->uploadedBy,
                        'file_name'        => $driveData['original_name'],
                        'file_path'        => $driveData['web_view_link'],
                        'original_name'    => $driveData['original_name'],
                        'mime_type'        => $driveData['mime_type'],
                        'size'             => $driveData['size'],
                        'drive_file_id'    => $driveData['drive_file_id'],
                        'drive_folder_id'  => $driveData['folder_id'] ?? null,
                        'web_view_link'    => $driveData['web_view_link'],
                        'web_content_link' => $driveData['web_content_link'],
                    ]);
                }

                // Hapus file sementara setelah berhasil diupload
                Storage::delete($tempFile['path']);

                Log::info('Attachment uploaded to Drive', [
                    'ticket_id' => $this->ticketId,
                    'file'      => $driveData['original_name'],
                    'folder'    => $driveData['folder_path'],
                ]);

            } catch (\Throwable $e) {
                Log::error('Failed to upload attachment to Drive', [
                    'ticket_id' => $this->ticketId,
                    'file'      => $tempFile['name'],
                    'error'     => $e->getMessage(),
                ]);
                throw $e; // trigger retry
            }
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('ProcessAttachmentToDriveJob FAILED after retries', [
            'ticket_id' => $this->ticketId,
            'error'     => $e->getMessage(),
        ]);
    }
}
