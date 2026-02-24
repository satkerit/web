<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\CacheService;

try {
    echo "Testing CacheService::getOffices()...\n";

    // Test getOffices tanpa parameter
    $offices = CacheService::getOffices();
    echo "Jumlah offices (all): " . $offices->count() . "\n";

    // Test getOffices dengan parameter
    $officesPusat = CacheService::getOffices('pusat');
    echo "Jumlah offices (pusat): " . $officesPusat->count() . "\n";

    // Test getOffices dengan parameter cabang
    $officesCabang = CacheService::getOffices('cabang');
    echo "Jumlah offices (cabang): " . $officesCabang->count() . "\n";

    echo "✅ CacheService::getOffices() berhasil!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}