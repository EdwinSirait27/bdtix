<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tickets;
use App\Jobs\SendOpenTicketWhatsapp;

use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::command('tickets:mark-overdue')->everyMinute();
Schedule::command('ticket:auto-review')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('tickets:send-overdue-reminder')
    ->dailyAt('15:23')
    ->withoutOverlapping();

    Schedule::call(function () {
    Tickets::where('status', 'Open')->each(function ($ticket) {
        SendOpenTicketWhatsapp::dispatch($ticket->id);
    });
})->everyTenMinutes();

Artisan::command('clamav:check', function (\App\Services\VirusScanner $scanner) {
    // String uji EICAR (tidak berbahaya) dipecah agar antivirus tidak menandai file ini.
    $eicar = 'X5O!P%@AP[4\PZX54(P^)7CC)7}$' . 'EICAR-STANDARD-ANTIVIRUS-TEST-FILE!$H+H*';
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $eicar);
    rewind($stream);

    try {
        $signature = $scanner->scan($stream);
    } catch (\RuntimeException $e) {
        $this->error($e->getMessage());

        return 1;
    }

    if ($signature === null) {
        $this->error('Scan tidak mendeteksi file uji EICAR. Cek CLAMAV_ENABLED dan database virus.');

        return 1;
    }

    $this->info("ClamAV berfungsi, file uji terdeteksi: {$signature}");

    return 0;
})->purpose('Pastikan scanner virus upload berfungsi dengan file uji EICAR');
