<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
class NextcloudService
{
    protected static function baseUrl(): string
    {
        return rtrim(config('services.nextcloud.base'), '/');
    }

    protected static function userRoot(): string
    {
        return '/remote.php/dav/files/' . config('services.nextcloud.username');
    }

    protected static function client()
    {
        return Http::withBasicAuth(
            config('services.nextcloud.username'),
            config('services.nextcloud.password')
        )
        ->timeout(60)
        ->retry(2, 500)
        ->withHeaders([
            'Accept' => '*/*',
        ]);
    }

    // =============================
    // CREATE DIRECTORY (SAFE)
    // =============================
    public static function makeDir(string $path): void
    {
        $path     = trim($path, '/');
        $segments = explode('/', $path);
        $current  = '';

        foreach ($segments as $segment) {
            $current = $current ? "{$current}/{$segment}" : $segment;
            $url = self::baseUrl() . self::userRoot() . '/' . $current;

            $response = self::client()->send('MKCOL', $url);

            // 201 = created, 405 = already exists
            if (!in_array($response->status(), [201, 405])) {
                Log::error('NEXTCLOUD_MKDIR_FAILED', [
                    'path'   => $current,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                throw new \Exception("Failed to create directory: {$current}");
            }
        }
    }

    // =============================
    // UPLOAD FILE (BINARY SAFE)
    // =============================
    public static function upload(
        string $path,
        string $filename,
        string $content,
        string $mime = 'application/octet-stream'
    ): void {
        $path = trim($path, '/');

        $url = self::baseUrl()
            . self::userRoot()
            . '/' . $path
            . '/' . rawurlencode($filename);

        $response = self::client()
            ->withHeaders([
                'Content-Type'   => $mime,
                'Content-Length' => strlen($content),
            ])
            ->withBody($content, 'application/octet-stream')
            ->put($url);

        // Nextcloud returns 201 or 204
        if (!in_array($response->status(), [201, 204])) {
            Log::error('NEXTCLOUD_UPLOAD_FAILED', [
                'file'   => $filename,
                'path'   => $path,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \Exception("Nextcloud upload failed: {$filename}");
        }
    }

    // =============================
    // SHARE FOLDER
    // =============================
    public static function shareFolder(string $path): string
    {
        $path = '/' . trim($path, '/');

        $response = self::client()
            ->withHeaders([
                'OCS-APIRequest' => 'true',
                'Accept'         => 'application/json',
            ])
            ->post(
                self::baseUrl()
                . '/ocs/v2.php/apps/files_sharing/api/v1/shares?format=json',
                [
                    'path'        => $path,
                    'shareType'   => 3, // public link
                    'permissions' => 1, // read only
                ]
            );

        $data = $response->json();

        $url = data_get($data, 'ocs.data.url');

        if (!$url) {
            Log::error('NEXTCLOUD_SHARE_FAILED', [
                'path'     => $path,
                'status'   => $response->status(),
                'response' => $data,
            ]);

            throw new \Exception('Failed to create Nextcloud share link');
        }

        return $url;
    }
    // protected static function userRoot(): string
    // {
    //     return '/remote.php/dav/files/' . config('services.nextcloud.username');
    // }

    // protected static function client()
    // {
    //     return Http::withBasicAuth(
    //         config('services.nextcloud.username'),
    //         config('services.nextcloud.password')
    //     );
    // }

    // public static function makeDir(string $path): void
    // {
    //     $path = trim($path, '/');
    //     $segments = explode('/', $path);
    //     $current = '';

    //     foreach ($segments as $segment) {
    //         $current = $current ? $current.'/'.$segment : $segment;

    //         try {
    //             self::client()->send(
    //                 'MKCOL',
    //                 config('services.nextcloud.base') .
    //                 self::userRoot() . '/' . $current
    //             );
    //         } catch (\Throwable $e) {
    //         }
    //     }
    // }

    // public static function upload(
    //     string $path,
    //     string $filename,
    //     string $content,
    //     string $mime
    // ): void {
    //     $path = trim($path, '/');

    //     self::client()
    //         ->withBody($content, $mime)
    //         ->put(
    //             config('services.nextcloud.base') .
    //             self::userRoot() . '/' . $path . '/' . $filename
    //         );
    // }

    // public static function shareFolder(string $path): string
    // {
    //     $path = trim($path, '/');

    //     $response = self::client()
    //         ->withHeaders([
    //             'OCS-APIRequest' => 'true',
    //             'Accept' => 'application/json',
    //         ])
    //         ->post(
    //             config('services.nextcloud.base') .
    //             '/ocs/v2.php/apps/files_sharing/api/v1/shares?format=json',
    //             [
    //                 'path'        => $path,
    //                 'shareType'   => 3,
    //                 'permissions' => 1,
    //             ]
    //         );

    //     $data = $response->json();
    //     $url  = data_get($data, 'ocs.data.url');

    //     if (!$url) {
    //         Log::error('Nextcloud share folder failed', [
    //             'path'     => $path,
    //             'response' => $data,
    //             'status'   => $response->status(),
    //         ]);
    //         throw new \Exception('Failed to create Nextcloud share link');
    //     }

    //     return $url;
    // }
}