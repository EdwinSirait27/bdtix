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