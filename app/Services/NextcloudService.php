<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NextcloudService
{
    protected static function userRoot(): string
{
    return '/remote.php/dav/files/' . config('services.nextcloud.username');
}

 
    protected static function client()
    {
        return Http::withBasicAuth(
            config('services.nextcloud.username'),
            config('services.nextcloud.password')
        );
    }

    
   public static function makeDir(string $path): void
{
    $path = trim($path, '/');

    try {
        self::client()->send(
            'MKCOL',
            config('services.nextcloud.base') .
            self::userRoot() . '/' . $path
        );
    } catch (\Throwable $e) {
        // folder sudah ada → aman
    }
}

public static function shareFolder(string $path): string
{
    $path = trim($path, '/'); // ⬅️ RELATIF USER ROOT

    $response = Http::withBasicAuth(
        config('services.nextcloud.username'),
        config('services.nextcloud.password')
    )
    ->withHeaders([
        'OCS-APIRequest' => 'true',
        'Accept'         => 'application/json',
    ])
    ->post(
        config('services.nextcloud.base') .
        '/ocs/v2.php/apps/files_sharing/api/v1/shares?format=json',
        [
            'path'        => $path, // ❗ TANPA leading slash
            'shareType'   => 3,
            'permissions' => 1,
        ]
    );

    $data = $response->json();
    $url  = data_get($data, 'ocs.data.url');

    if (!$url) {
        Log::error('Nextcloud share folder failed', [
            'path'     => $path,
            'response' => $data,
            'status'   => $response->status(),
        ]);

        throw new \Exception('Failed to create Nextcloud share link');
    }

    return $url;
}
   public static function upload(
    string $path,
    string $filename,
    string $content,
    string $mime
): void {
    $path = trim($path, '/');

    self::client()
        ->withBody($content, $mime)
        ->put(
            config('services.nextcloud.base') .
            self::userRoot() . '/' . $path . '/' . $filename
        );
}

}
   // public static function share(string $path): string
    // {
    //     $response = Http::withBasicAuth(
    //         config('services.nextcloud.username'),
    //         config('services.nextcloud.password')
    //     )
    //     ->asForm()
    //     ->post(
    //         config('services.nextcloud.base') . '/ocs/v2.php/apps/files_sharing/api/v1/shares',
    //         [
    //             'path' => "/{$path}",
    //             'shareType' => 3, // public link
    //             'permissions' => 1 // read only
    //         ]
    //     );

    //     return $response->json('ocs.data.url');
    // }
    // public static function makeDir(string $path): void
    // {
    //     self::client()->send(
    //         'MKCOL',
    //         config('services.nextcloud.base') .
    //         config('services.nextcloud.dav') .
    //         "/{$path}"
    //     );
    // }