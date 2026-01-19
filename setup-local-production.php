<?php

/**
 * Setup Local Production Simulation
 * 
 * Script ini akan mengkonfigurasi environment local untuk mensimulasikan
 * bagaimana website akan terlihat di production.
 */

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Setting up Local Production Simulation...\n";
echo "============================================\n\n";

// Colors for output
function printSuccess($message) {
    echo "✅ $message\n";
}

function printInfo($message) {
    echo "ℹ️  $message\n";
}

function printWarning($message) {
    echo "⚠️  $message\n";
}

function printError($message) {
    echo "❌ $message\n";
}

// 1. Create dev directory structure
printInfo("Creating production-like directory structure...");

$devDir = public_path('dev');
if (!is_dir($devDir)) {
    mkdir($devDir, 0755, true);
    printSuccess("Created public/dev directory");
} else {
    printInfo("public/dev directory already exists");
}

// 2. Create storage copy (since symlink requires admin permission)
$storageLink = $devDir . '/storage';
$storageTarget = storage_path('app/public');

// Remove existing directory if exists
if (is_dir($storageLink)) {
    printInfo("Storage directory already exists");
} else {
    // Create storage directory and copy files
    if (mkdir($storageLink, 0755, true)) {
        printSuccess("Created storage directory: public/dev/storage");
        
        // Copy storage files
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storageTarget, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $target = $storageLink . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item, $target);
            }
        }
        printSuccess("Copied storage files to dev directory");
    } else {
        printError("Failed to create storage directory");
    }
}

// 3. Copy index.php to dev directory
$indexSource = public_path('index.php');
$indexTarget = $devDir . '/index.php';

if (copy($indexSource, $indexTarget)) {
    printSuccess("Copied index.php to dev directory");
} else {
    printWarning("Failed to copy index.php");
}

// 4. Update .htaccess if exists
$htaccessSource = public_path('.htaccess');
$htaccessTarget = $devDir . '/.htaccess';

if (file_exists($htaccessSource)) {
    if (copy($htaccessSource, $htaccessTarget)) {
        printSuccess("Copied .htaccess to dev directory");
    } else {
        printWarning("Failed to copy .htaccess");
    }
}

// 5. Test storage helper
printInfo("Testing StorageHelper with production simulation...");

use App\Helpers\StorageHelper;
use App\Models\CompanyInfo;

// Test company logo
$company = CompanyInfo::getInfo();
if ($company && $company->logo) {
    $logoUrl = StorageHelper::url($company->logo);
    printSuccess("Logo URL: $logoUrl");
    
    // Check if URL contains /dev/storage
    if (strpos($logoUrl, '/dev/storage') !== false) {
        printSuccess("✅ StorageHelper is using production-like URL structure");
    } else {
        printWarning("StorageHelper is not using production URL structure");
        printInfo("Current URL: $logoUrl");
        printInfo("Expected to contain: /dev/storage");
    }
} else {
    printWarning("No company logo found for testing");
}

// 6. Clear and cache config
printInfo("Clearing and caching configuration...");

try {
    \Artisan::call('config:clear');
    printSuccess("Configuration cache cleared");
    
    \Artisan::call('config:cache');
    printSuccess("Configuration cached");
    
    \Artisan::call('view:clear');
    printSuccess("View cache cleared");
    
} catch (Exception $e) {
    printError("Error with artisan commands: " . $e->getMessage());
}

echo "\n";
printSuccess("🎉 Local Production Simulation Setup Complete!");
echo "\n";

printInfo("📋 What was configured:");
echo "  • Created public/dev directory structure\n";
echo "  • Created storage symlink: public/dev/storage\n";
echo "  • Updated .env with production-like STORAGE_URL\n";
echo "  • Copied necessary files to dev directory\n";
echo "  • Cached configuration\n";
echo "\n";

printInfo("🌐 How to test:");
echo "  • Access your website normally: http://localhost/cms_baru\n";
echo "  • Images should now use URLs like: http://localhost/dev/storage/...\n";
echo "  • This simulates how it will work in production\n";
echo "\n";

printInfo("🔍 Verification:");
echo "  • Open browser developer tools\n";
echo "  • Check image URLs in Network tab\n";
echo "  • URLs should contain '/dev/storage/'\n";
echo "\n";

printWarning("📝 Note:");
echo "  • This is a simulation of production environment\n";
echo "  • Your local environment is still 'local'\n";
echo "  • StorageHelper will use production-like URLs due to STORAGE_URL setting\n";
echo "\n";

printInfo("🚀 Ready to test production-like behavior!");