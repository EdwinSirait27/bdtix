<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\AutoReviewService;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::command('tickets:mark-overdue')->everyMinute();
Schedule::call(function () {
    app(AutoReviewService::class)->run();
})->everyFiveMinutes();
