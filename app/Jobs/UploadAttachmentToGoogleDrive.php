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
        public string $folderIdentity,
        public string $category,
        public string $type = 'user',
        public string $modelType = 'user',
        public ?string $filePrefix = null,
    ) {}

    public function handle(GoogleDriveService $driveService): void
{
    // Gunakan model yang sesuai
    $attachment = $this->modelType === 'executor'
        ? \App\Models\TicketExecutorAttachment::find($this->attachmentId)
        : \App\Models\Ticketattachments::find($this->attachmentId);

    if (!$attachment) {
        Log::warning("Attachment {$this->attachmentId} not found.");
        return;
    }

        try {
            $tempFullPath = storage_path('app/private/' . $this->tempPath);

            if (!file_exists($tempFullPath)) {
                Log::error("Temp file not found: {$tempFullPath}");
                return;
            }

            $driveData = $driveService->uploadFromPath(
                $tempFullPath,
                $attachment->original_name,
                $attachment->mime_type,
                $this->folderIdentity,
                $this->category,
                $this->type,
                $this->filePrefix
            );

            $attachment->update([
                'file_path'        => $driveData['web_view_link'],
                'drive_file_id'    => $driveData['drive_file_id'],
                'drive_folder_id'  => $driveData['folder_id'] ?? null,
                'web_view_link'    => $driveData['web_view_link'],
                'web_content_link' => $driveData['web_content_link'],
                'status'           => 'uploaded',
            ]);

            @unlink($tempFullPath);

        } catch (\Exception $e) {
            Log::error("Failed to upload: " . $e->getMessage());
            $attachment->update(['status' => 'failed']);
            throw $e;
        }
    }
}
