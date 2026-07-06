<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function temporaryPublicUrl(string $path, int $minutes = 5): string
    {
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));

        return str_replace(
            rtrim(config('filesystems.disks.s3.endpoint'), '/'),
            rtrim(config('filesystems.disks.s3.url'), '/'),
            $url
        );
    }

    public static function publicUrl(string $path): string
    {
        $endpoint = rtrim(config('filesystems.disks.s3.url'), '/');
        return $endpoint . '/' . ltrim($path, '/');
    }
}