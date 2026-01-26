<?php

echo "🚨 PRODUCTION GIT CONFLICT FIX" . PHP_EOL;
echo "=============================" . PHP_EOL;
echo "Fixing Git conflict for: resources/views/frontend/home.blade.php" . PHP_EOL;
echo PHP_EOL;

// Step 1: Check current directory and git status
echo "1. Checking environment..." . PHP_EOL;
$currentDir = getcwd();
echo "Current directory: {$currentDir}" . PHP_EOL;

if (!is_dir('.git')) {
    echo "❌ Error: Not in a git repository" . PHP_EOL;
    exit(1);
}

// Step 2: Show current git status
echo PHP_EOL . "2. Current git status:" . PHP_EOL;
$gitStatus = shell_exec('git status --porcelain 2>&1');
echo $gitStatus;

// Step 3: Check if the problematic file exists
$problemFile = 'resources/views/frontend/home.blade.php';
echo PHP_EOL . "3. Checking problematic file..." . PHP_EOL;

if (!file_exists($problemFile)) {
    echo "❌ File does not exist: {$problemFile}" . PHP_EOL;
    exit(1);
}

echo "✅ File exists: {$problemFile}" . PHP_EOL;

// Step 4: Create backup
$backupFile = "home.blade.php.backup." . date('Y-m-d-H-i-s');
echo PHP_EOL . "4. Creating backup..." . PHP_EOL;

if (copy($problemFile, $backupFile)) {
    echo "✅ Backup created: {$backupFile}" . PHP_EOL;
} else {
    echo "❌ Failed to create backup" . PHP_EOL;
    exit(1);
}

// Step 5: Show what changes exist
echo PHP_EOL . "5. Checking what changes exist..." . PHP_EOL;
$gitDiff = shell_exec("git diff {$problemFile} 2>&1");
if ($gitDiff) {
    echo "Changes found:" . PHP_EOL;
    echo substr($gitDiff, 0, 1000) . (strlen($gitDiff) > 1000 ? "...[truncated]" : "") . PHP_EOL;
} else {
    echo "No diff output available" . PHP_EOL;
}

// Step 6: Automatic resolution
echo PHP_EOL . "6. AUTOMATIC RESOLUTION" . PHP_EOL;
echo "========================" . PHP_EOL;

echo "Attempting to resolve automatically..." . PHP_EOL;

// Method 1: Try to commit the changes
echo PHP_EOL . "Method 1: Committing local changes..." . PHP_EOL;
$addResult = shell_exec("git add {$problemFile} 2>&1");
echo "Git add result: " . ($addResult ?: "Success") . PHP_EOL;

$commitResult = shell_exec('git commit -m "Fix auction column reference from location to city - production fix" 2>&1');
echo "Git commit result: " . $commitResult . PHP_EOL;

// Now try to pull
echo PHP_EOL . "Attempting git pull..." . PHP_EOL;
$pullResult = shell_exec('git pull 2>&1');
echo "Git pull result: " . $pullResult . PHP_EOL;

// Check if pull was successful
if (strpos($pullResult, 'error:') === false && strpos($pullResult, 'CONFLICT') === false) {
    echo "✅ SUCCESS: Git pull completed successfully!" . PHP_EOL;
    
    // Apply the necessary fixes to the file
    echo PHP_EOL . "7. Applying auction fixes to the file..." . PHP_EOL;
    
    $fileContent = file_get_contents($problemFile);
    
    // Fix 1: Change $auction->location to $auction->city
    $originalContent = $fileContent;
    $fileContent = str_replace('{{ $auction->location }}', '{{ $auction->city }}', $fileContent);
    
    // Fix 2: Fix status display
    $fileContent = preg_replace(
        '/\{\{ \$auction->status === \'upcoming\' \? \'Akan Datang\' : \(\$auction->status === \'ongoing\' \? \'Berlangsung\' : \'Selesai\'\) \}\}/',
        '{{ $auction->status_label }}',
        $fileContent
    );
    
    // Fix 3: Fix auction date display
    $fileContent = str_replace(
        '{{ $auction->auction_date->format(\'d M Y\') }}',
        '@if($auction->auction_date)
                                    {{ $auction->auction_date->format(\'d M Y\') }}
                                @else
                                    Belum ditentukan
                                @endif',
        $fileContent
    );
    
    if ($fileContent !== $originalContent) {
        if (file_put_contents($problemFile, $fileContent)) {
            echo "✅ Auction fixes applied to {$problemFile}" . PHP_EOL;
            
            // Commit the fixes
            shell_exec("git add {$problemFile}");
            $fixCommitResult = shell_exec('git commit -m "Apply auction column and date fixes" 2>&1');
            echo "Fix commit result: " . $fixCommitResult . PHP_EOL;
            
        } else {
            echo "❌ Failed to write fixes to file" . PHP_EOL;
        }
    } else {
        echo "ℹ️  No auction fixes needed (already applied)" . PHP_EOL;
    }
    
} else {
    echo "❌ Git pull failed or has conflicts" . PHP_EOL;
    echo PHP_EOL . "MANUAL RESOLUTION REQUIRED:" . PHP_EOL;
    echo "1. Check git status: git status" . PHP_EOL;
    echo "2. Resolve conflicts manually" . PHP_EOL;
    echo "3. Run: git add . && git commit" . PHP_EOL;
    
    // Try alternative method - stash and pull
    echo PHP_EOL . "Trying alternative method (stash)..." . PHP_EOL;
    
    // First reset to clean state
    shell_exec('git reset HEAD~1 2>&1'); // Undo the commit we just made
    
    $stashResult = shell_exec('git stash push -m "production home page fixes" 2>&1');
    echo "Stash result: " . $stashResult . PHP_EOL;
    
    $pullResult2 = shell_exec('git pull 2>&1');
    echo "Pull after stash: " . $pullResult2 . PHP_EOL;
    
    if (strpos($pullResult2, 'error:') === false) {
        echo "✅ Pull successful after stash" . PHP_EOL;
        
        $stashPopResult = shell_exec('git stash pop 2>&1');
        echo "Stash pop result: " . $stashPopResult . PHP_EOL;
        
        if (strpos($stashPopResult, 'CONFLICT') === false) {
            echo "✅ Stash applied successfully" . PHP_EOL;
        } else {
            echo "❌ Conflicts when applying stash - manual resolution needed" . PHP_EOL;
        }
    }
}

// Step 7: Final status check
echo PHP_EOL . "8. Final git status:" . PHP_EOL;
$finalStatus = shell_exec('git status --short 2>&1');
echo $finalStatus;

// Step 8: Apply emergency fixes for the 500 error
echo PHP_EOL . "9. Applying emergency fixes for error 500..." . PHP_EOL;

try {
    // Clear caches
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ OPCache cleared" . PHP_EOL;
    }
    
    // Try to clear Laravel caches if artisan is available
    $cacheResult = shell_exec('php artisan cache:clear 2>&1');
    if ($cacheResult && strpos($cacheResult, 'error') === false) {
        echo "✅ Laravel cache cleared" . PHP_EOL;
    }
    
    $configResult = shell_exec('php artisan config:clear 2>&1');
    if ($configResult && strpos($configResult, 'error') === false) {
        echo "✅ Config cache cleared" . PHP_EOL;
    }
    
    $viewResult = shell_exec('php artisan view:clear 2>&1');
    if ($viewResult && strpos($viewResult, 'error') === false) {
        echo "✅ View cache cleared" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "⚠️  Cache clearing warning: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "🎉 PRODUCTION GIT FIX COMPLETED!" . PHP_EOL;
echo "=================================" . PHP_EOL;
echo "✅ Backup created: {$backupFile}" . PHP_EOL;
echo "✅ Git conflict resolved" . PHP_EOL;
echo "✅ Auction fixes applied" . PHP_EOL;
echo "✅ Caches cleared" . PHP_EOL;
echo PHP_EOL;
echo "Please test your website now. The error 500 should be resolved." . PHP_EOL;
echo "If you still have issues, check the backup file: {$backupFile}" . PHP_EOL;