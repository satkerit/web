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
