<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Adjust this path to point to your app directory
// Structure: public_html/dev/index.php -> ../../app
$appBaseDir = __DIR__ . '/../../app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appBaseDir . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
if (file_exists($appBaseDir . '/vendor/autoload.php')) {
    require $appBaseDir . '/vendor/autoload.php';
} else {
    die("Critical Error: vendor/autoload.php not found at $appBaseDir/vendor/autoload.php");
}

// Bootstrap Laravel and handle the request...
$app = require_once $appBaseDir . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
