<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageHelper
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'txt'];
    private const INLINE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

    /**
     * Ekstensi dari klien tidak bisa dipercaya: rule `mimes` hanya memeriksa isi file,
     * sehingga PNG berisi skrip dengan nama "x.html" tetap lolos validasi.
     */
    public static function safeExtension(UploadedFile $file): string
    {
        $extension = Str::lower($file->getClientOriginalExtension());

        if (in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            return $extension;
        }

        $guessed = $file->guessExtension();

        return in_array($guessed, self::ALLOWED_EXTENSIONS, true) ? $guessed : 'bin';
    }

    /**
     * Nama file dengan komponen acak: tidak bisa ditebak dan tidak menimpa file lain.
     */
    public static function attachmentFileName(string $prefix, UploadedFile $file): string
    {
        return $prefix . '-' . Str::lower(Str::random(12)) . '.' . self::safeExtension($file);
    }

    /**
     * Mengalirkan file dari S3 melalui aplikasi, jadi setiap akses melewati cek login.
     * Hanya gambar dan PDF yang ditampilkan di browser, selain itu selalu diunduh.
     */
    public static function attachmentResponse(string $path, string $fileName, ?string $mimeType): StreamedResponse
    {
        $disk = Storage::disk('s3');

        abort_unless($disk->exists($path), 404, 'File not found.');

        $inline = in_array($mimeType, self::INLINE_MIME_TYPES, true);

        $headers = [
            'Content-Type' => $inline ? $mimeType : 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ];

        // Penampil PDF bawaan browser tidak berjalan di dalam sandbox.
        if ($mimeType !== 'application/pdf') {
            $headers['Content-Security-Policy'] = "default-src 'none'; sandbox";
        }

        return $disk->response($path, $fileName, $headers, $inline ? 'inline' : 'attachment');
    }

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