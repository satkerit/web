<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\AboutController;
use Illuminate\Http\Request;

try {
    echo "Testing AboutController::offices directly...\n";
    
    // Buat request dengan parameter type=pusat
    $request = new Request(['type' => 'pusat']);
    
    $controller = new AboutController();
    $response = $controller->offices($request);
    
    echo "✅ AboutController::offices berhasil!\n";
    echo "Response status: " . $response->status() . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}