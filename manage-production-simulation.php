<?php

/**
 * Manage Production Simulation
 * 
 * Script untuk mengelola production simulation di local environment
 */

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎛️  Production Simulation Manager\n";
echo "=================================\n\n";

// Check command line arguments
$command = $argv[1] ?? 'status';

switch ($command) {
    case 'enable':
        enableProductionSimulation();
        break;
    case 'disable':
        disableProductionSimulation();
        break;
    case 'status':
        showStatus();
        break;
    case 'test':
        runTests();
        break;
    case 'help':
        showHelp();
        break;
    default:
        echo "❌ Unknown command: $command\n";
        showHelp();
        break;
}

function enableProductionSimulation() {
    echo "🚀 Enabling Production Simulation...\n\n";
    
    // Update .env file
    $envPath = base_path('.env');
    $envContent = file_get_contents($envPath);
    
    // Check if STORAGE_URL already exists
    if (strpos($envContent, 'STORAGE_URL=') !== false) {
        // Update existing
        $envContent = preg_replace('/STORAGE_URL=.*/', 'STORAGE_URL=http://localhost/dev/storage', $envContent);
        echo "✅ Updated STORAGE_URL in .env\n";
    } else {
        // Add new
        $envContent .= "\n# Production Simulation\nSTORAGE_URL=http://localhost/dev/storage\n";
        echo "✅ Added STORAGE_URL to .env\n";
    }
    
    file_put_contents($envPath, $envContent);
    
    // Clear config cache
    \Artisan::call('config:clear');
    echo "✅ Configuration cache cleared\n";
    
    // Create directory structure
    $devDir = public_path('dev');
    if (!is_dir($devDir)) {
        mkdir($devDir, 0755, true);
        echo "✅ Created public/dev directory\n";
    }
    
    // Create storage junction
    $storageDir = $devDir . '/storage';
    if (is_dir($storageDir)) {
        echo "ℹ️  Storage directory already exists\n";
    } else {
        // Try to create junction (Windows) or symlink (Linux)
        $target = storage_path('app/public');
        if (PHP_OS_FAMILY === 'Windows') {
            exec("mklink /J \"$storageDir\" \"$target\"", $output, $returnCode);
        } else {
            $returnCode = symlink($target, $storageDir) ? 0 : 1;
        }
        
        if ($returnCode === 0) {
            echo "✅ Created storage junction/symlink\n";
        } else {
            echo "⚠️  Could not create junction/symlink automatically\n";
            echo "   Please run as administrator or create manually\n";
        }
    }
    
    echo "\n🎉 Production simulation enabled!\n";
    echo "🌐 Test at: http://localhost/cms_baru\n";
}

function disableProductionSimulation() {
    echo "🛑 Disabling Production Simulation...\n\n";
    
    // Update .env file
    $envPath = base_path('.env');
    $envContent = file_get_contents($envPath);
    
    // Remove or comment out STORAGE_URL
    $envContent = preg_replace('/STORAGE_URL=.*/', '# STORAGE_URL=http://localhost/dev/storage', $envContent);
    file_put_contents($envPath, $envContent);
    echo "✅ Disabled STORAGE_URL in .env\n";
    
    // Clear config cache
    \Artisan::call('config:clear');
    echo "✅ Configuration cache cleared\n";
    
    echo "\n✅ Production simulation disabled!\n";
    echo "🌐 Website will use standard storage URLs\n";
}

function showStatus() {
    echo "📊 Production Simulation Status\n";
    echo "==============================\n\n";
    
    // Check environment
    $storageUrl = env('STORAGE_URL');
    $isEnabled = $storageUrl && strpos($storageUrl, '/dev/storage') !== false;
    
    echo "Status: " . ($isEnabled ? "✅ ENABLED" : "❌ DISABLED") . "\n";
    echo "APP_ENV: " . config('app.env') . "\n";
    echo "STORAGE_URL: " . ($storageUrl ?: 'Not set') . "\n";
    
    // Check directory structure
    $devDir = public_path('dev');
    $storageDir = $devDir . '/storage';
    
    echo "\nDirectory Structure:\n";
    echo "public/dev: " . (is_dir($devDir) ? "✅ Exists" : "❌ Missing") . "\n";
    echo "public/dev/storage: " . (is_dir($storageDir) ? "✅ Exists" : "❌ Missing") . "\n";
    
    if (is_dir($storageDir)) {
        if (is_link($storageDir)) {
            echo "Storage Type: ✅ Junction/Symlink\n";
        } else {
            echo "Storage Type: ⚠️  Regular Directory\n";
        }
    }
    
    // Test URL generation
    if ($isEnabled) {
        echo "\nURL Generation Test:\n";
        $testUrl = \App\Helpers\StorageHelper::url('company/logo.png');
        echo "Sample URL: $testUrl\n";
        
        if (strpos($testUrl, '/dev/storage') !== false) {
            echo "URL Structure: ✅ Production-like\n";
        } else {
            echo "URL Structure: ❌ Standard\n";
        }
    }
    
    echo "\n";
}

function runTests() {
    echo "🧪 Running Production Simulation Tests...\n\n";
    
    // Run verification script
    echo "Running verification...\n";
    include 'verify-production-simulation.php';
}

function showHelp() {
    echo "📖 Production Simulation Manager - Help\n";
    echo "=======================================\n\n";
    
    echo "Usage: php manage-production-simulation.php [command]\n\n";
    
    echo "Commands:\n";
    echo "  enable    Enable production simulation\n";
    echo "  disable   Disable production simulation\n";
    echo "  status    Show current status\n";
    echo "  test      Run verification tests\n";
    echo "  help      Show this help message\n\n";
    
    echo "Examples:\n";
    echo "  php manage-production-simulation.php enable\n";
    echo "  php manage-production-simulation.php status\n";
    echo "  php manage-production-simulation.php test\n\n";
    
    echo "What Production Simulation Does:\n";
    echo "• Changes storage URLs to use /dev/storage/ structure\n";
    echo "• Creates production-like directory structure\n";
    echo "• Allows testing how website will look in production\n";
    echo "• No impact on actual functionality\n\n";
}

echo "\n";