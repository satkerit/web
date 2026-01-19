<?php

/**
 * Test Production Simulation
 * 
 * Script untuk test bagaimana StorageHelper akan bekerja dengan
 * konfigurasi production di environment local.
 */

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Helpers\StorageHelper;
use App\Models\CompanyInfo;
use App\Models\HeroSlide;
use App\Models\Product;

echo "🧪 Testing Production Simulation in Local Environment\n";
echo "====================================================\n\n";

// Test current configuration
echo "⚙️  Current Configuration:\n";
echo "APP_ENV: " . config('app.env') . "\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "STORAGE_URL (from config): " . config('storage-production.production_paths.storage_url', 'Not set') . "\n";
echo "STORAGE_URL (from env): " . env('STORAGE_URL', 'Not set') . "\n";
echo "\n";

// Test StorageHelper URL generation
echo "🔧 Testing StorageHelper URL Generation:\n";

$testPaths = [
    'company/logo.png',
    'hero-slides/slide1.jpg',
    'products/product1.jpg',
    'news/article1.jpg'
];

foreach ($testPaths as $path) {
    $url = StorageHelper::url($path);
    echo "  Path: $path\n";
    echo "  URL:  $url\n";
    
    // Check if URL contains production-like structure
    if (strpos($url, '/dev/storage') !== false) {
        echo "  ✅ Using production-like URL structure\n";
    } else {
        echo "  ⚠️  Using standard URL structure\n";
    }
    echo "\n";
}

// Test with real company data
echo "📋 Testing with Real Company Data:\n";
$company = CompanyInfo::getInfo();

if ($company) {
    echo "✅ Company info loaded\n";
    
    if ($company->logo) {
        $logoUrl = StorageHelper::url($company->logo);
        echo "🖼️  Logo URL: $logoUrl\n";
        
        if (strpos($logoUrl, '/dev/storage') !== false) {
            echo "✅ Logo URL uses production structure\n";
        } else {
            echo "⚠️  Logo URL uses standard structure\n";
        }
    }
    
    if ($company->logo_footer) {
        $logoFooterUrl = StorageHelper::url($company->logo_footer);
        echo "🖼️  Logo Footer URL: $logoFooterUrl\n";
        
        if (strpos($logoFooterUrl, '/dev/storage') !== false) {
            echo "✅ Logo Footer URL uses production structure\n";
        } else {
            echo "⚠️  Logo Footer URL uses standard structure\n";
        }
    }
} else {
    echo "❌ No company info found\n";
}

echo "\n";

// Test Hero Slides
echo "🎠 Testing Hero Slides URLs:\n";
$heroSlides = HeroSlide::take(2)->get();

foreach ($heroSlides as $index => $slide) {
    if ($slide->image) {
        $slideUrl = StorageHelper::url($slide->image);
        echo "  Slide " . ($index + 1) . ": $slideUrl\n";
        
        if (strpos($slideUrl, '/dev/storage') !== false) {
            echo "  ✅ Production structure\n";
        } else {
            echo "  ⚠️  Standard structure\n";
        }
    }
}

echo "\n";

// Test Products
echo "📦 Testing Product URLs:\n";
$products = Product::take(2)->get();

foreach ($products as $index => $product) {
    if ($product->image) {
        $productUrl = StorageHelper::url($product->image);
        echo "  Product " . ($index + 1) . ": $productUrl\n";
        
        if (strpos($productUrl, '/dev/storage') !== false) {
            echo "  ✅ Production structure\n";
        } else {
            echo "  ⚠️  Standard structure\n";
        }
    }
}

echo "\n";

// Summary
echo "📊 Summary:\n";
echo "==========\n";

$envStorageUrl = env('STORAGE_URL');
if ($envStorageUrl && strpos($envStorageUrl, '/dev/storage') !== false) {
    echo "✅ Environment configured for production simulation\n";
    echo "✅ StorageHelper will generate production-like URLs\n";
    echo "✅ Frontend will display images with /dev/storage URLs\n";
    echo "\n";
    echo "🌐 How to test in browser:\n";
    echo "1. Open your website: http://localhost/cms_baru\n";
    echo "2. Open Developer Tools (F12)\n";
    echo "3. Go to Network tab\n";
    echo "4. Refresh page and check image URLs\n";
    echo "5. Image URLs should contain '/dev/storage/'\n";
    echo "\n";
    echo "📝 Note: Images may not load because /dev/storage path doesn't exist\n";
    echo "This is expected - we're simulating production URL structure\n";
} else {
    echo "⚠️  Environment not configured for production simulation\n";
    echo "⚠️  StorageHelper will use standard URLs\n";
    echo "\n";
    echo "🔧 To enable production simulation:\n";
    echo "1. Add to .env: STORAGE_URL=http://localhost/dev/storage\n";
    echo "2. Run: php artisan config:clear\n";
    echo "3. Run this test again\n";
}

echo "\n";
echo "🎉 Test completed!\n";