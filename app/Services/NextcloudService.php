<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
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
    $segments = explode('/', $path);
    $current = '';

    foreach ($segments as $segment) {
        $current = $current ? $current.'/'.$segment : $segment;

        $url =
            config('services.nextcloud.base') .
            self::userRoot() . '/' . $current;

        $response = self::client()->send('MKCOL', $url);

        // 201 = created, 405 = already exists
        if (!in_array($response->status(), [201, 405])) {
            throw new \Exception(
                'MKCOL failed: ' . $response->status()
            );
        }
    }
}
    public static function upload(
    string $path,
    string $filename,
    string $localPath,
    string $mime
): void {
    $path = trim($path, '/');

    $fullUrl =
        config('services.nextcloud.base') .
        self::userRoot() . '/' . $path . '/' . $filename;

    $stream = fopen($localPath, 'r');

    if (!$stream) {
        throw new \Exception("Cannot open file stream: {$localPath}");
    }

    $response = self::client()
        ->withBody($stream, $mime)
        ->put($fullUrl);

    fclose($stream);

    if (!$response->successful()) {
        throw new \Exception(
            'Nextcloud upload failed: ' .
            $response->status() . ' ' . $response->body()
        );
    }
}


    public static function shareFolder(string $path): string
    {
        $path = trim($path, '/');

        $response = self::client()
            ->withHeaders([
                'OCS-APIRequest' => 'true',
                'Accept' => 'application/json',
            ])
            ->post(
                config('services.nextcloud.base') .
                '/ocs/v2.php/apps/files_sharing/api/v1/shares?format=json',
                [
                    'path'        => $path,
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
}