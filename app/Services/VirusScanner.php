<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Klien protokol INSTREAM untuk daemon ClamAV (clamd).
 *
 * Melempar RuntimeException bila scan tidak bisa dipastikan selesai, supaya pemanggil
 * tidak pernah memperlakukan "scanner mati" sebagai "file bersih".
 */
class VirusScanner
{
    private const CHUNK_BYTES = 8192;

    /**
     * @return string|null Nama virus bila terdeteksi, null bila bersih.
     */
    public function scanLocalFile(string $path): ?string
    {
        $stream = Storage::disk('local')->readStream($path);

        if (! is_resource($stream)) {
            throw new RuntimeException("Cannot read file for virus scan: {$path}");
        }

        try {
            return $this->scan($stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * @param  resource  $stream
     * @return string|null Nama virus bila terdeteksi, null bila bersih.
     */
    public function scan($stream): ?string
    {
        if (! config('services.clamav.enabled')) {
            Log::warning('VIRUS_SCAN_SKIPPED: CLAMAV_ENABLED=false');

            return null;
        }

        $connection = $this->connect();

        try {
            $this->send($connection, "zINSTREAM\0");

            while (! feof($stream)) {
                $chunk = fread($stream, self::CHUNK_BYTES);

                if ($chunk === false) {
                    throw new RuntimeException('Failed reading file during virus scan.');
                }

                if ($chunk !== '') {
                    $this->send($connection, pack('N', strlen($chunk)) . $chunk);
                }
            }

            $this->send($connection, pack('N', 0));

            return $this->parseReply($this->readReply($connection));
        } finally {
            fclose($connection);
        }
    }

    /**
     * @return resource
     */
    private function connect()
    {
        $socket = config('services.clamav.socket');
        $address = $socket
            ? "unix://{$socket}"
            : 'tcp://' . config('services.clamav.host') . ':' . config('services.clamav.port');
        $timeout = (int) config('services.clamav.timeout');

        $connection = @stream_socket_client($address, $errorCode, $errorMessage, $timeout);

        if (! $connection) {
            throw new RuntimeException("Cannot connect to ClamAV at {$address}: {$errorMessage}");
        }

        stream_set_timeout($connection, $timeout);

        return $connection;
    }

    /**
     * @param  resource  $connection
     */
    private function send($connection, string $data): void
    {
        while ($data !== '') {
            $written = @fwrite($connection, $data);

            if ($written === false || $written === 0) {
                throw new RuntimeException('ClamAV connection closed during scan.');
            }

            $data = substr($data, $written);
        }
    }

    /**
     * @param  resource  $connection
     */
    private function readReply($connection): string
    {
        $reply = '';

        while (! feof($connection) && ! str_contains($reply, "\0")) {
            $part = fread($connection, 1024);

            if ($part === false || stream_get_meta_data($connection)['timed_out']) {
                throw new RuntimeException('ClamAV did not reply in time.');
            }

            $reply .= $part;
        }

        return trim($reply, "\0\n\r ");
    }

    private function parseReply(string $reply): ?string
    {
        if ($reply === 'stream: OK') {
            return null;
        }

        if (preg_match('/^stream: (.+) FOUND$/', $reply, $matches)) {
            return $matches[1];
        }

        throw new RuntimeException("Unexpected ClamAV reply: {$reply}");
    }
}
