<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule session cleanup to run every hour
Schedule::command('sessions:clean-expired')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// Schedule blocked IPs cleanup to run daily
Schedule::command('security:cleanup-blocked-ips')
    ->daily()
    ->at('03:00')
    ->withoutOverlapping()
    ->runInBackground();

// Schedule password history cleanup to run yearly
Schedule::command('password-history:cleanup --days=365')
    ->yearly()
    ->withoutOverlapping()
    ->runInBackground();
