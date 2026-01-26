<?php
/**
 * CLEAR CACHE SCRIPT - PRODUCTION
 * Untuk membersihkan cache tanpa akses shell
 * Akses via browser: https://yourdomain.com/clear_cache.php
 */

echo "<h2>🧹 CLEARING PRODUCTION CACHE</h2>";
echo "<hr>";

$cleared = [];
$errors = [];

// 1. Clear OPCache
echo "<h3>1. OPCache</h3>";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✅ OPCache cleared successfully<br>";
        $cleared[] = "OPCache";
    } else {
        echo "❌ Failed to clear OPCache<br>";
        $errors[] = "OPCache";
    }
} else {
    echo "ℹ️ OPCache not available<br>";
}

// 2. Clear Laravel Bootstrap Cache
echo "<h3>2. Laravel Bootstrap Cache</h3>";
$bootstrapCacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php', 
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php'
];

foreach ($bootstrapCacheFiles as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "✅ Deleted: {$file}<br>";
            $cleared[] = $file;
        } else {
            echo "❌ Failed to delete: {$file}<br>";
            $errors[] = $file;
        }
    } else {
        echo "ℹ️ Not found: {$file}<br>";
    }
}

// 3. Clear Storage Framework Cache
echo "<h3>3. Storage Framework Cache</h3>";
$storageCachePath = 'storage/framework/cache/data';
if (is_dir($storageCachePath)) {
    $files = glob($storageCachePath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "✅ Cleared {$count} cache files from storage<br>";
    $cleared[] = "Storage cache ({$count} files)";
} else {
    echo "ℹ️ Storage cache directory not found<br>";
}

// 4. Clear View Cache
echo "<h3>4. View Cache</h3>";
$viewCachePath = 'storage/framework/views';
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "✅ Cleared {$count} compiled view files<br>";
    $cleared[] = "View cache ({$count} files)";
} else {
    echo "ℹ️ View cache directory not found<br>";
}

// 5. Clear Session Files (optional)
echo "<h3>5. Session Files</h3>";
$sessionPath = 'storage/framework/sessions';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            // Only delete old session files (older than 1 hour)
            if (filemtime($file) < (time() - 3600)) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }
    }
    echo "✅ Cleared {$count} old session files<br>";
    $cleared[] = "Old sessions ({$count} files)";
} else {
    echo "ℹ️ Session directory not found<br>";
}

// 6. Clear Application Cache (if exists)
echo "<h3>6. Application Cache</h3>";
$appCachePath = 'storage/app/cache';
if (is_dir($appCachePath)) {
    $files = glob($appCachePath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "✅ Cleared {$count} application cache files<br>";
    $cleared[] = "App cache ({$count} files)";
} else {
    echo "ℹ️ Application cache directory not found<br>";
}

// Summary
echo "<hr>";
echo "<h3>📊 SUMMARY</h3>";

if (!empty($cleared)) {
    echo "<h4 style='color: green;'>✅ Successfully Cleared:</h4>";
    echo "<ul>";
    foreach ($cleared as $item) {
        echo "<li>{$item}</li>";
    }
    echo "</ul>";
}

if (!empty($errors)) {
    echo "<h4 style='color: red;'>❌ Errors:</h4>";
    echo "<ul>";
    foreach ($errors as $item) {
        echo "<li>{$item}</li>";
    }
    echo "</ul>";
}

echo "<hr>";
echo "<h3>🎉 CACHE CLEARING COMPLETED!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Test your website now</li>";
echo "<li>Check if error 500 is resolved</li>";
echo "<li>Delete this file (clear_cache.php) after use</li>";
echo "</ol>";

echo "<hr>";
echo "<p><small>Generated: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small>⚠️ Remember to delete this file after use for security!</small></p>";
?>