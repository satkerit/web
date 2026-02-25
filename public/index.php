<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Ubah jalur ke maintenance mode (jika ada)
if (file_exists($maintenance = __DIR__.'/../app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Ubah jalur ke Autoloader Composer
require __DIR__.'/../vendor/autoload.php';

// 3. Ubah jalur ke Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
