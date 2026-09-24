<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily 24-hour reminder email dispatch at 8:00 AM
Schedule::command('appointments:send-reminders')->dailyAt('08:00');

// Schedule hourly cleanup of expired unbooked slots
Schedule::command('appointments:cleanup-expired-slots')->hourly();
