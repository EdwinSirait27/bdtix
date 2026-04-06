<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
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
    
    Schedule::command('ticket:dispatch-open-reminder')
    ->dailyAt('10:10')
    ->withoutOverlapping();