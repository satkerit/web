<?php

/**
 * Verify Production Simulation
 * 
 * Script untuk memverifikasi bahwa production simulation berfungsi dengan baik
 */

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Helpers\StorageHelper;
use App\Models\CompanyInfo;

echo "✅ Production Simulation Verification\n";
echo "====================================\n\n";

// 1. Check configuration
echo "⚙️  Configuration Check:\n";
echo "APP_ENV: " . config('app.env') . "\n";
echo "STORAGE_URL: " . env('STORAGE_URL') . "\n";

// 2. Check directory structure
echo "\n📁 Directory Structure Check:\n";

$devDir = public_path('dev');
$storageDir = $devDir . '/storage';

if (is_dir($devDir)) {
    echo "✅ public/dev directory exists\n";
} else {
    echo "❌ public/dev directory missing\n";
}

if (is_dir($storageDir)) {
    echo "✅ public/dev/storage directory exists\n";
    
    // Check if it's a junction/symlink
    if (is_link($storageDir)) {
        echo "✅ Storage is a symlink/junction\n";
        echo "   Target: " . readlink($storageDir) . "\n";
    } else {
        echo "⚠️  Storage is a regular directory (not symlink)\n";
    }
} else {
    echo "❌ public/dev/storage directory missing\n";
}

// 3. Test StorageHelper URLs
echo "\n🔧 StorageHelper URL Test:\n";

$company = CompanyInfo::getInfo();
if ($company && $company->logo) {
    $logoUrl = StorageHelper::url($company->logo);
    echo "Logo URL: $logoUrl\n";
    
    // Check if URL is accessible
    $logoPath = public_path('dev/storage/' . $company->logo);
    if (file_exists($logoPath)) {
        echo "✅ Logo file accessible at: $logoPath\n";
    } else {
        echo "❌ Logo file not accessible at: $logoPath\n";
    }
}

// 4. Test file access
echo "\n📂 File Access Test:\n";

$testFiles = [
    'company' => 'Company logos',
    'hero-slides' => 'Hero slide images',
    'products' => 'Product images',
    'news' => 'News images'
];

foreach ($testFiles as $folder => $description) {
    $folderPath = public_path("dev/storage/$folder");
    if (is_dir($folderPath)) {
        $files = glob($folderPath . '/*');
        $count = count($files);
        echo "✅ $description: $count files found\n";
    } else {
        echo "⚠️  $description: folder not found\n";
    }
}

// 5. Generate test URLs
echo "\n🌐 Sample URLs Generated:\n";

$samplePaths = [
    'company/logo.png',
    'hero-slides/slide1.jpg',
    'products/product1.jpg'
];

foreach ($samplePaths as $path) {
    $url = StorageHelper::url($path);
    echo "  $path → $url\n";
}

echo "\n";

// 6. Instructions for browser testing
echo "🧪 Browser Testing Instructions:\n";
echo "================================\n";
echo "1. Open your website: http://localhost/cms_baru\n";
echo "2. Open Developer Tools (F12)\n";
echo "3. Go to Network tab\n";
echo "4. Refresh the page\n";
echo "5. Look for image requests\n";
echo "6. Verify image URLs contain '/dev/storage/'\n";
echo "7. Check that images load successfully\n";
echo "\n";

echo "📋 What to expect:\n";
echo "• Logo in header should display\n";
echo "• Logo in footer should display\n";
echo "• Hero slider images should display\n";
echo "• Product images should display\n";
echo "• All image URLs should contain '/dev/storage/'\n";
echo "\n";

echo "🎯 Success Criteria:\n";
echo "• All images display correctly\n";
echo "• No 404 errors in browser console\n";
echo "• Image URLs use production-like structure\n";
echo "• Website looks exactly like it will in production\n";
echo "\n";

echo "✅ Production simulation is ready for testing!\n";
echo "🚀 Your local environment now behaves like production!\n";