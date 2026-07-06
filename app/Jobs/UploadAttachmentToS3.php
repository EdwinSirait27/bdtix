<?php

namespace App\Jobs;

use App\Models\Ticketattachments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadAttachmentToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        private string $attachmentId,
        private string $tempPath,
        private string $s3Folder,
        private string $fileName,
    ) {}

   public function handle(): void
{
    $attachment = Ticketattachments::findOrFail($this->attachmentId);

    try {
        // Pastikan file temp ada sebelum proses
        if (!Storage::disk('local')->exists($this->tempPath)) {
            Log::error('UploadAttachmentToS3 temp file not found', [
                'attachment_id' => $this->attachmentId,
                'temp_path'     => $this->tempPath,
            ]);
            $attachment->update(['status' => 'failed']);
            return;
        }

        $fileContent = Storage::disk('local')->get($this->tempPath);

        if ($fileContent === null) {
            Log::error('UploadAttachmentToS3 file content null', [
                'attachment_id' => $this->attachmentId,
                'temp_path'     => $this->tempPath,
            ]);
            $attachment->update(['status' => 'failed']);
            return;
        }

        Storage::disk('s3')->put(
            "{$this->s3Folder}/{$this->fileName}",
            $fileContent,
            'private'
        );

        $attachment->update([
            'file_path' => "{$this->s3Folder}/{$this->fileName}",
            'status'    => 'uploaded',
        ]);

        Storage::disk('local')->delete($this->tempPath);

        Log::info('UploadAttachmentToS3 success', [
            'attachment_id' => $this->attachmentId,
            'file_path'     => "{$this->s3Folder}/{$this->fileName}",
        ]);

    } catch (\Throwable $e) {
        $attachment->update(['status' => 'failed']);

        Log::error('UploadAttachmentToS3 failed', [
            'attachment_id' => $this->attachmentId,
            'temp_path'     => $this->tempPath,
            'error'         => $e->getMessage(),
        ]);

        throw $e;
    }
}
}