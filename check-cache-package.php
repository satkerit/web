<?php

/**
 * Quick Check Script - Verify Response Cache Package
 * Run: php check-cache-package.php
 */

echo "========================================\n";
echo "Response Cache Package Check\n";
echo "========================================\n\n";

// Check if we're in Laravel root
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel root directory\n";
    exit(1);
}

// Check composer.json
echo "1. Checking composer.json...\n";
$composerJson = json_decode(file_get_contents('composer.json'), true);
if (isset($composerJson['require']['spatie/laravel-responsecache'])) {
    $version = $composerJson['require']['spatie/laravel-responsecache'];
    echo "   ✅ Package listed in composer.json: {$version}\n\n";
} else {
    echo "   ❌ Package NOT found in composer.json\n\n";
}

// Check if vendor directory exists
echo "2. Checking vendor directory...\n";
$vendorPath = 'vendor/spatie/laravel-responsecache';
if (is_dir($vendorPath)) {
    echo "   ✅ Package directory exists: {$vendorPath}\n";
    
    // Check for the middleware class
    $middlewarePath = $vendorPath . '/src/Middlewares/CacheResponse.php';
    if (file_exists($middlewarePath)) {
        echo "   ✅ Middleware class file exists\n\n";
    } else {
        echo "   ❌ Middleware class file NOT found\n\n";
    }
} else {
    echo "   ❌ Package directory NOT found: {$vendorPath}\n";
    echo "   ⚠️  Run: composer install\n\n";
}

// Check composer.lock
echo "3. Checking composer.lock...\n";
if (file_exists('composer.lock')) {
    $composerLock = json_decode(file_get_contents('composer.lock'), true);
    $found = false;
    
    foreach ($composerLock['packages'] ?? [] as $package) {
        if ($package['name'] === 'spatie/laravel-responsecache') {
            echo "   ✅ Package locked at version: {$package['version']}\n\n";
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo "   ❌ Package NOT found in composer.lock\n\n";
    }
} else {
    echo "   ❌ composer.lock not found\n\n";
}

// Check autoload files
echo "4. Checking autoloader...\n";
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    
    if (class_exists('Spatie\ResponseCache\Middlewares\CacheResponse')) {
        echo "   ✅ Middleware class can be autoloaded\n\n";
    } else {
        echo "   ❌ Middleware class CANNOT be autoloaded\n";
        echo "   ⚠️  Run: composer dump-autoload\n\n";
    }
} else {
    echo "   ❌ Autoloader not found\n\n";
}

// Check config
echo "5. Checking configuration...\n";
if (file_exists('config/responsecache.php')) {
    echo "   ✅ Config file exists: config/responsecache.php\n\n";
} else {
    echo "   ⚠️  Config file not published\n";
    echo "   Run: php artisan vendor:publish --provider=\"Spatie\\ResponseCache\\ResponseCacheServiceProvider\"\n\n";
}

// Check bootstrap/app.php
echo "6. Checking middleware registration...\n";
$bootstrapApp = file_get_contents('bootstrap/app.php');
if (strpos($bootstrapApp, 'Spatie\ResponseCache\Middlewares\CacheResponse') !== false) {
    if (strpos($bootstrapApp, '// \Spatie\ResponseCache\Middlewares\CacheResponse') !== false) {
        echo "   ⚠️  Middleware is COMMENTED OUT in bootstrap/app.php\n";
        echo "   This is intentional until package is properly installed\n\n";
    } else {
        echo "   ✅ Middleware is registered in bootstrap/app.php\n\n";
    }
} else {
    echo "   ❌ Middleware NOT registered in bootstrap/app.php\n\n";
}

echo "========================================\n";
echo "Summary\n";
echo "========================================\n\n";

if (is_dir($vendorPath) && class_exists('Spatie\ResponseCache\Middlewares\CacheResponse')) {
    echo "✅ Package is properly installed and ready to use!\n";
    echo "   You can uncomment the middleware in bootstrap/app.php\n\n";
} else {
    echo "❌ Package needs to be installed\n";
    echo "   Run: composer install --no-dev --optimize-autoloader\n\n";
}

echo "For detailed instructions, see: DEPLOYMENT_INSTRUCTIONS.md\n\n";
