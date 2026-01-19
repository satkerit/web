<?php

/**
 * Test News Images - Comprehensive Testing
 * 
 * Script untuk test apakah semua gambar berita tampil dengan benar
 * di admin panel dan frontend setelah redesign.
 */

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\News;
use App\Helpers\StorageHelper;

echo "🧪 Testing News Images - Comprehensive Test\n";
echo "==========================================\n\n";

// Test News Data
echo "📰 Testing News Data:\n";
$news = News::with('images')->take(5)->get();

if ($news->count() > 0) {
    echo "✅ Found {$news->count()} news articles\n\n";
    
    foreach ($news as $index => $article) {
        echo "📄 Article " . ($index + 1) . ": {$article->title}\n";
        echo "   Category: {$article->category}\n";
        echo "   Published: " . ($article->is_published ? 'Yes' : 'No') . "\n";
        
        // Test featured image
        if ($article->featured_image) {
            $featuredUrl = StorageHelper::url($article->featured_image);
            echo "   🖼️  Featured Image URL: {$featuredUrl}\n";
            echo "   📁 Featured Image exists: " . (StorageHelper::exists($article->featured_image) ? 'YES' : 'NO') . "\n";
            
            // Check URL structure
            if (strpos($featuredUrl, '/dev/storage') !== false) {
                echo "   ✅ Using production-like URL structure\n";
            } else {
                echo "   ⚠️  Using standard URL structure\n";
            }
        } else {
            echo "   ⚠️  No featured image\n";
        }
        
        // Test gallery images
        if ($article->images->count() > 0) {
            echo "   🎨 Gallery Images: {$article->images->count()}\n";
            foreach ($article->images as $imgIndex => $image) {
                $imageUrl = StorageHelper::url($image->image_path);
                echo "     Image " . ($imgIndex + 1) . ": {$imageUrl}\n";
                echo "     Exists: " . (StorageHelper::exists($image->image_path) ? 'YES' : 'NO') . "\n";
            }
        } else {
            echo "   📷 No gallery images\n";
        }
        
        echo "\n";
    }
} else {
    echo "❌ No news articles found\n";
}

// Test URL Generation Patterns
echo "🔧 Testing URL Generation Patterns:\n";
$testPaths = [
    'news/featured-image.jpg',
    'news/gallery-image-1.jpg',
    'news/gallery-image-2.jpg'
];

foreach ($testPaths as $path) {
    $url = StorageHelper::url($path);
    echo "  Path: {$path}\n";
    echo "  URL:  {$url}\n";
    
    if (strpos($url, '/dev/storage') !== false) {
        echo "  ✅ Production-like structure\n";
    } else {
        echo "  ⚠️  Standard structure\n";
    }
    echo "\n";
}

// Test File Access
echo "📂 Testing File Access:\n";
$newsStoragePath = storage_path('app/public/news');
if (is_dir($newsStoragePath)) {
    $files = glob($newsStoragePath . '/*');
    $count = count($files);
    echo "✅ News storage directory exists\n";
    echo "📁 Found {$count} files in news directory\n";
    
    // Test access via production simulation
    $devStoragePath = public_path('dev/storage/news');
    if (is_dir($devStoragePath)) {
        $devFiles = glob($devStoragePath . '/*');
        $devCount = count($devFiles);
        echo "✅ Production simulation path exists\n";
        echo "📁 Found {$devCount} files in dev/storage/news\n";
        
        if ($count === $devCount) {
            echo "✅ File counts match - symlink working correctly\n";
        } else {
            echo "⚠️  File counts don't match - check symlink\n";
        }
    } else {
        echo "❌ Production simulation path not found\n";
    }
} else {
    echo "⚠️  News storage directory not found\n";
}

echo "\n";

// Test Frontend URLs
echo "🌐 Testing Frontend URL Generation:\n";
$sampleNews = $news->first();
if ($sampleNews) {
    echo "Sample Article: {$sampleNews->title}\n";
    
    if ($sampleNews->featured_image) {
        $frontendUrl = StorageHelper::url($sampleNews->featured_image);
        echo "Frontend Featured Image URL: {$frontendUrl}\n";
        
        // Test if URL would work in browser
        $expectedPath = public_path('dev/storage/' . $sampleNews->featured_image);
        if (file_exists($expectedPath)) {
            echo "✅ File accessible via frontend URL\n";
        } else {
            echo "❌ File NOT accessible via frontend URL\n";
            echo "   Expected path: {$expectedPath}\n";
        }
    }
    
    // Test gallery images
    foreach ($sampleNews->images as $image) {
        $galleryUrl = StorageHelper::url($image->image_path);
        echo "Gallery Image URL: {$galleryUrl}\n";
        
        $expectedPath = public_path('dev/storage/' . $image->image_path);
        if (file_exists($expectedPath)) {
            echo "✅ Gallery image accessible\n";
        } else {
            echo "❌ Gallery image NOT accessible\n";
        }
    }
}

echo "\n";

// Summary and Recommendations
echo "📊 Test Summary:\n";
echo "===============\n";

$totalNews = $news->count();
$newsWithFeatured = $news->where('featured_image', '!=', null)->count();
$newsWithGallery = $news->filter(function($article) {
    return $article->images->count() > 0;
})->count();

echo "📰 Total News Articles: {$totalNews}\n";
echo "🖼️  Articles with Featured Image: {$newsWithFeatured}\n";
echo "🎨 Articles with Gallery Images: {$newsWithGallery}\n";

$storageUrl = env('STORAGE_URL');
if ($storageUrl && strpos($storageUrl, '/dev/storage') !== false) {
    echo "✅ Production simulation enabled\n";
    echo "✅ StorageHelper generating production-like URLs\n";
} else {
    echo "⚠️  Production simulation not enabled\n";
}

echo "\n";

echo "🎯 What to Test in Browser:\n";
echo "==========================\n";
echo "1. Admin Panel:\n";
echo "   - Go to: http://localhost/cms_baru/admin/news\n";
echo "   - Check if featured images display in news list\n";
echo "   - Edit a news article and check image previews\n";
echo "   - Upload new images and verify they appear\n";
echo "\n";
echo "2. Frontend:\n";
echo "   - Go to: http://localhost/cms_baru/news\n";
echo "   - Check if featured images display in news list\n";
echo "   - Click on a news article to view details\n";
echo "   - Verify image slideshow works correctly\n";
echo "   - Check that all gallery images load\n";
echo "\n";
echo "3. Developer Tools:\n";
echo "   - Open F12 and go to Network tab\n";
echo "   - Refresh pages and check image URLs\n";
echo "   - Verify URLs contain '/dev/storage/'\n";
echo "   - Ensure no 404 errors for images\n";
echo "\n";

echo "✅ News image testing completed!\n";
echo "🚀 Ready for browser testing!\n";