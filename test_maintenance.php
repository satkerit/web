<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;

try {
    echo "Testing SiteSetting maintenance mode...\n";
    
    $settings = SiteSetting::getSettings();
    echo "Maintenance mode: " . ($settings->maintenance_mode ?? 'not set') . "\n";
    echo "Maintenance message: " . ($settings->maintenance_message ?? 'not set') . "\n";
    
    echo "✅ SiteSetting test berhasil!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}