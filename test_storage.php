<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Helpers\StorageHelper;

try {
    echo "Testing StorageHelper::getConfig()...\n";
    $config = StorageHelper::getConfig();
    print_r($config);
    
    echo "\nTesting StorageHelper::verifyStorageLink()...\n";
    $verify = StorageHelper::verifyStorageLink();
    print_r($verify);
    
    echo "\nTesting StorageHelper::url()...\n";
    $url = StorageHelper::url('test.jpg');
    echo "URL: " . $url . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}