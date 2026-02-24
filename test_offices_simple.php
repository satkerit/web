<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\CacheService;

try {
    echo "Testing view offices dengan data...\n";
    
    // Ambil data offices
    $offices = CacheService::getOffices('pusat');
    echo "Jumlah offices: " . $offices->count() . "\n";
    
    if ($offices->count() > 0) {
        $office = $offices->first();
        echo "Office pertama: " . $office->name . "\n";
        echo "Type: " . $office->type . "\n";
        echo "Type label: " . $office->type_label . "\n";
        echo "Has coordinates: " . ($office->has_coordinates ? 'Yes' : 'No') . "\n";
        echo "Photo: " . ($office->photo ?? 'No photo') . "\n";
        
        // Test StorageHelper
        if ($office->photo) {
            $url = \App\Helpers\StorageHelper::url($office->photo);
            echo "Photo URL: " . $url . "\n";
        }
    }
    
    echo "✅ Test view offices berhasil!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}