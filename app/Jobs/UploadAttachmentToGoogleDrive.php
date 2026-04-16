<?php

namespace App\Jobs;

use App\Models\Ticketattachments;
use App\Models\TicketExecutorAttachment;
use App\Services\GoogleDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UploadAttachmentToGoogleDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public string $attachmentId,
        public string $tempPath,
        public string $folderIdentity, // NIP
        public string $category,
        public string $type = 'user',      // user / executor
        public string $modelType = 'user', // user / executor
        public ?string $filePrefix = null,
    ) {}

    public function handle(GoogleDriveService $driveService): void
    {
        // =============================
        // 1. AMBIL MODEL
        // =============================
        $attachment = $this->modelType === 'executor'
            ? TicketExecutorAttachment::find($this->attachmentId)
            : Ticketattachments::find($this->attachmentId);

        if (!$attachment) {
            Log::warning("Attachment {$this->attachmentId} not found.");
            return;
        }

        try {
            // =============================
            // 2. PATH FILE TEMP
            // =============================
            $tempFullPath = storage_path('app/private/' . $this->tempPath);

            // fallback jika tidak ada di private
            if (!file_exists($tempFullPath)) {
                $tempFullPath = storage_path('app/' . $this->tempPath);
            }

            if (!file_exists($tempFullPath)) {
                Log::error("Temp file not found: {$tempFullPath}");
                $attachment->update(['status' => 'failed']);
                return;
            }

            // =============================
            // 3. UPLOAD KE GOOGLE DRIVE
            // =============================
            $driveData = $driveService->uploadFromPath(
                $tempFullPath,
                $attachment->original_name,
                $attachment->mime_type,
                $this->folderIdentity, // NIP (string)
                $this->category,       // category (string)
                $this->type,           // user / executor
                $this->filePrefix      // prefix opsional
            );

            // =============================
            // 4. UPDATE DB
            // =============================
            $attachment->update([
                'file_path'        => $driveData['web_view_link'],
                'drive_file_id'    => $driveData['drive_file_id'],
                'drive_folder_id'  => $driveData['folder_id'] ?? null,
                'web_view_link'    => $driveData['web_view_link'],
                'web_content_link' => $driveData['web_content_link'],
                'status'           => 'uploaded',
            ]);
            // =============================
            // 5. HAPUS FILE TEMP
            // =============================
            @unlink($tempFullPath);
            Log::info("Attachment {$this->attachmentId} uploaded successfully.");
        } catch (\Exception $e) {
            Log::error("Failed to upload attachment {$this->attachmentId}: " . $e->getMessage());
            $attachment->update(['status' => 'failed']);
            throw $e;
        }
    }
}