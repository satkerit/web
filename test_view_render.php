<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\CacheService;

try {
    echo "Testing view render...\n";
    
    // Ambil data offices
    $offices = CacheService::getOffices('pusat');
    echo "Jumlah offices: " . $offices->count() . "\n";
    
    // Coba render view
    $view = view('frontend.pages.about.offices', ['offices' => $offices]);
    $html = $view->render();
    
    echo "✅ View berhasil dirender!\n";
    echo "Panjang HTML: " . strlen($html) . " karakter\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}