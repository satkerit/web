<?php

/**
 * CMS Repair Script
 * Upload this file to: public_html/dev/fix_production.php
 * Access it via: https://dev.bprsbabel.id/fix_production.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>CMS Repair Tool</h1>";

// 1. Determine Paths
$publicDir = __DIR__;
// Assuming structure: public_html/dev/index.php -> app is in ../../app
$appBaseDir = realpath($publicDir . '/../../app');

echo "<strong>Public Dir:</strong> " . $publicDir . "<br>";
echo "<strong>App Base Dir:</strong> " . ($appBaseDir ?: '<span style="color:red">NOT FOUND</span>') . "<br><hr>";

if (!$appBaseDir || !is_dir($appBaseDir)) {
    die("<h2 style='color:red'>Error: Cannot locate App directory!</h2>Check if '../../app' is correct relative to this file.");
}

// 2. Clear Bootstrap Cache (CRITICAL FIX)
echo "<h3>1. Clearing Bootstrap Cache...</h3>";
$cacheDir = $appBaseDir . '/bootstrap/cache';
$cacheFiles = ['config.php', 'services.php', 'packages.php', 'routes-v7.php', 'events.php'];
$deletedCount = 0;

foreach ($cacheFiles as $file) {
    $path = $cacheDir . '/' . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "<span style='color:green'>Deleted: $file</span><br>";
            $deletedCount++;
        } else {
            echo "<span style='color:red'>Failed to delete: $file (Permission Denied)</span><br>";
        }
    } else {
        echo "<span style='color:gray'>Not found: $file</span><br>";
    }
}

if ($deletedCount > 0) {
    echo "<strong>Success: Configuration cache cleared!</strong><br>";
} else {
    echo "Cache was already clear.<br>";
}

// 3. Check Vendor Directory
echo "<h3>2. Checking Vendor Directory...</h3>";
if (is_dir($appBaseDir . '/vendor')) {
    echo "<span style='color:green'>Vendor directory exists.</span><br>";
} else {
    echo "<span style='color:red'>CRITICAL: Vendor directory missing! Please run 'composer install'.</span><br>";
}

// 4. Create/Fix Storage Directories
echo "<h3>3. Checking Storage Directories...</h3>";
$dirsToCreate = [
    '/storage/app/public',
    '/storage/framework/cache/data',
    '/storage/framework/sessions',
    '/storage/framework/testing',
    '/storage/framework/views',
    '/storage/logs',
];

foreach ($dirsToCreate as $dir) {
    $fullPath = $appBaseDir . $dir;
    if (!file_exists($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "Created: $dir<br>";
        } else {
            echo "<span style='color:red'>Failed to create: $dir</span><br>";
        }
    } else {
        // Try to write a test file
        if (is_writable($fullPath)) {
            echo "<span style='color:green'>OK (Writable): $dir</span><br>";
        } else {
            echo "<span style='color:red'>ERROR (Not Writable): $dir</span><br>";
        }
    }
}

echo "<hr>";
echo "<h2>Done!</h2>";
echo "<p>Please delete this file (fix_production.php) after use.</p>";
echo "<a href='/'>Go to Homepage</a>";
