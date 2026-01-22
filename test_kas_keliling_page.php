<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::create('/produk-layanan/kas-keliling', 'GET');
$response = $kernel->handle($request);

echo "HTTP Status: " . $response->getStatusCode() . "\n";
echo "Content Length: " . strlen($response->getContent()) . " bytes\n\n";

// Check if schedules are in the response
$content = $response->getContent();

if (strpos($content, 'Tidak Ada Jadwal') !== false) {
    echo "❌ Page shows 'Tidak Ada Jadwal'\n";
} elseif (strpos($content, 'Jadwal 5 Hari Terdekat') !== false) {
    echo "✅ Page shows 'Jadwal 5 Hari Terdekat'\n";
    
    // Count schedule items
    $count = substr_count($content, 'class="p-6 hover:bg-gray-50 transition-colors"');
    echo "✅ Found {$count} schedule items in HTML\n";
} else {
    echo "⚠️  Cannot determine page content\n";
}

// Check for specific area names
$areas = ['Pasar Pagi Sungailiat', 'Pasar Belinyu', 'Kelurahan Pemali'];
foreach ($areas as $area) {
    if (strpos($content, $area) !== false) {
        echo "✅ Found: {$area}\n";
    }
}

$kernel->terminate($request, $response);
