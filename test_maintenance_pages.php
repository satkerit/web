<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;

try {
    echo "Testing maintenance pages...\n";
    
    $settings = SiteSetting::getSettings();
    echo "Maintenance mode: " . ($settings->maintenance_mode ? 'ON' : 'OFF') . "\n";
    echo "Maintenance pages: " . json_encode($settings->maintenance_pages ?? []) . "\n";
    
    // Test path untuk offices
    $path = 'tentang-kami/kantor-cabang';
    $isUnderMaintenance = SiteSetting::isPageUnderMaintenance($path);
    echo "Path '{$path}' under maintenance: " . ($isUnderMaintenance ? 'YES' : 'NO') . "\n";
    
    // Test path dengan parameter
    $pathWithParam = 'tentang-kami/kantor-cabang?type=pusat';
    $isUnderMaintenance2 = SiteSetting::isPageUnderMaintenance($pathWithParam);
    echo "Path '{$pathWithParam}' under maintenance: " . ($isUnderMaintenance2 ? 'YES' : 'NO') . "\n";
    
    echo "✅ Maintenance test berhasil!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}