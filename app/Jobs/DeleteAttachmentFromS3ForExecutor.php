<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class DeleteAttachmentFromS3ForExecutor implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 10;

    public function __construct(private string $filePath) {}

    public function handle(): void
    {
        try {
            if (Storage::disk('s3')->exists($this->filePath)) {
                Storage::disk('s3')->delete($this->filePath);

                Log::info('ATTACHMENT_S3ForExecutor_DELETED', [
                    'file_path' => $this->filePath,
                ]);
            } else {
                Log::warning('ATTACHMENT_S3ForExecutor_NOT_FOUND', [
                    'file_path' => $this->filePath,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('ATTACHMENT_S3ForExecutor_DELETE_FAILED', [
                'file_path' => $this->filePath,
                'error'     => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}