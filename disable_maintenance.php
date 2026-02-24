<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;

try {
    echo "Disabling maintenance mode...\n";
    
    $settings = SiteSetting::getFreshSettings();
    $settings->update([
        'maintenance_mode' => false,
        'maintenance_pages' => [],
        'maintenance_end_time' => null
    ]);
    
    echo "✅ Maintenance mode disabled!\n";
    echo "Maintenance mode: " . ($settings->maintenance_mode ? 'ON' : 'OFF') . "\n";
    echo "Maintenance pages: " . json_encode($settings->maintenance_pages ?? []) . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}