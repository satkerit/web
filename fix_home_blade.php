<?php
/**
 * FIX HOME BLADE FILE - PRODUCTION
 * Script untuk memperbaiki file home.blade.php secara otomatis
 * Upload file ini ke root folder dan akses via browser
 */

echo "<h2>🔧 FIXING HOME.BLADE.PHP FILE</h2>";
echo "<hr>";

$homeBladeFile = 'resources/views/frontend/home.blade.php';

// Check if file exists
if (!file_exists($homeBladeFile)) {
    echo "<p style='color: red;'>❌ Error: File not found: {$homeBladeFile}</p>";
    echo "<p>Please make sure you're in the correct directory.</p>";
    exit;
}

echo "<p>✅ File found: {$homeBladeFile}</p>";

// Create backup
$backupFile = 'home.blade.php.backup.' . date('Y-m-d-H-i-s');
if (copy($homeBladeFile, $backupFile)) {
    echo "<p>✅ Backup created: {$backupFile}</p>";
} else {
    echo "<p style='color: red;'>❌ Failed to create backup</p>";
    exit;
}

// Read current content
$content = file_get_contents($homeBladeFile);
$originalContent = $content;

echo "<h3>Applying fixes...</h3>";

// Fix 1: Change $auction->location to $auction->city
$fix1Before = '{{ $auction->location }}';
$fix1After = '{{ $auction->city }}';

if (strpos($content, $fix1Before) !== false) {
    $content = str_replace($fix1Before, $fix1After, $content);
    echo "<p>✅ Fix 1: Changed \$auction->location to \$auction->city</p>";
} else {
    echo "<p>ℹ️ Fix 1: \$auction->location not found (might be already fixed)</p>";
}

// Fix 2: Fix status display
$fix2Before = "{{ \$auction->status === 'upcoming' ? 'Akan Datang' : (\$auction->status === 'ongoing' ? 'Berlangsung' : 'Selesai') }}";
$fix2After = '{{ $auction->status_label }}';

// Try different variations of the status code
$statusPatterns = [
    "/\{\{\s*\\\$auction->status\s*===\s*'upcoming'\s*\?\s*'Akan Datang'\s*:\s*\(\\\$auction->status\s*===\s*'ongoing'\s*\?\s*'Berlangsung'\s*:\s*'Selesai'\)\s*\}\}/",
    "/\{\{\s*\\\$auction->status\s*===\s*['\"]upcoming['\"]\s*\?\s*['\"]Akan Datang['\"]\s*:\s*\(\\\$auction->status\s*===\s*['\"]ongoing['\"]\s*\?\s*['\"]Berlangsung['\"]\s*:\s*['\"]Selesai['\"]\)\s*\}\}/"
];

$fix2Applied = false;
foreach ($statusPatterns as $pattern) {
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $fix2After, $content);
        echo "<p>✅ Fix 2: Fixed status display to use status_label</p>";
        $fix2Applied = true;
        break;
    }
}

if (!$fix2Applied) {
    echo "<p>ℹ️ Fix 2: Status display pattern not found (might be already fixed)</p>";
}

// Fix 3: Fix auction date display with null check
$fix3Before = '{{ $auction->auction_date->format(\'d M Y\') }}';
$fix3After = '@if($auction->auction_date)
                                    {{ $auction->auction_date->format(\'d M Y\') }}
                                @else
                                    Belum ditentukan
                                @endif';

if (strpos($content, $fix3Before) !== false) {
    $content = str_replace($fix3Before, $fix3After, $content);
    echo "<p>✅ Fix 3: Added null check for auction_date</p>";
} else {
    echo "<p>ℹ️ Fix 3: Auction date pattern not found (might be already fixed)</p>";
}

// Fix 4: Fix status badge colors
$fix4Before = "{{ \$auction->status === 'upcoming' ? 'bg-yellow-500 text-white' : (\$auction->status === 'ongoing' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white') }}";
$fix4After = "{{ \$auction->status === 'published' ? 'bg-blue-500 text-white' : (\$auction->status === 'registration_open' ? 'bg-green-500 text-white' : (\$auction->status === 'auction_scheduled' ? 'bg-yellow-500 text-white' : 'bg-gray-500 text-white')) }}";

$badgePatterns = [
    "/\{\{\s*\\\$auction->status\s*===\s*'upcoming'\s*\?\s*'bg-yellow-500 text-white'\s*:\s*\(\\\$auction->status\s*===\s*'ongoing'\s*\?\s*'bg-green-500 text-white'\s*:\s*'bg-gray-500 text-white'\)\s*\}\}/"
];

$fix4Applied = false;
foreach ($badgePatterns as $pattern) {
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $fix4After, $content);
        echo "<p>✅ Fix 4: Fixed status badge colors</p>";
        $fix4Applied = true;
        break;
    }
}

if (!$fix4Applied) {
    echo "<p>ℹ️ Fix 4: Status badge pattern not found (might be already fixed)</p>";
}

// Check if any changes were made
if ($content !== $originalContent) {
    // Write the fixed content back to file
    if (file_put_contents($homeBladeFile, $content)) {
        echo "<h3 style='color: green;'>✅ SUCCESS: File has been fixed!</h3>";
        echo "<p>Changes applied to: {$homeBladeFile}</p>";
        echo "<p>Backup saved as: {$backupFile}</p>";
    } else {
        echo "<h3 style='color: red;'>❌ ERROR: Failed to write changes to file</h3>";
        echo "<p>Please check file permissions.</p>";
    }
} else {
    echo "<h3 style='color: blue;'>ℹ️ INFO: No changes needed</h3>";
    echo "<p>The file appears to be already fixed or doesn't contain the expected patterns.</p>";
}

echo "<hr>";
echo "<h3>📋 NEXT STEPS:</h3>";
echo "<ol>";
echo "<li>Run the cache clearing script: <a href='clear_cache.php' target='_blank'>clear_cache.php</a></li>";
echo "<li>Test your website home page</li>";
echo "<li>If still having issues, check the database fixes</li>";
echo "<li>Delete this file and clear_cache.php after use</li>";
echo "</ol>";

echo "<hr>";
echo "<p><small>Generated: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small>⚠️ Remember to delete this file after use for security!</small></p>";
?>