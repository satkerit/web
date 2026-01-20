<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\StorageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TestStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test storage configuration and verify symlink is working';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("🔍 Testing Storage Configuration");
        $this->newLine();
        
        // Show configuration
        $this->showConfiguration();
        $this->newLine();
        
        // Verify symlink
        $this->verifySymlink();
        $this->newLine();
        
        // Test file operations
        $this->testFileOperations();
        $this->newLine();
        
        return self::SUCCESS;
    }
    
    /**
     * Show current storage configuration
     */
    protected function showConfiguration(): void
    {
        $config = StorageHelper::getConfig();
        
        $this->info("📋 Current Configuration:");
        $this->table(
            ['Setting', 'Value'],
            [
                ['Storage Mode', $config['mode']],
                ['Environment', $config['environment']],
                ['Storage Root', $config['storage_root']],
                ['Storage URL', $config['storage_url']],
                ['Production Public Path', $config['production_public_path'] ?? 'Not set'],
                ['Symlink From', $config['symlink_from'] ?? 'Not configured'],
                ['Symlink To', $config['symlink_to'] ?? 'Not configured'],
            ]
        );
    }
    
    /**
     * Verify symbolic link
     */
    protected function verifySymlink(): void
    {
        $this->info("🔗 Verifying Symbolic Link:");
        
        $verification = StorageHelper::verifyStorageLink();
        
        $this->table(
            ['Check', 'Status'],
            [
                ['Link Path', $verification['link_path'] ?? 'N/A'],
                ['Target Path', $verification['target_path'] ?? 'N/A'],
                ['Target Exists', $verification['target_exists'] ? '✅ Yes' : '❌ No'],
                ['Link Exists', $verification['link_exists'] ? '✅ Yes' : '❌ No'],
                ['Link Valid', $verification['link_valid'] ? '✅ Yes' : '❌ No'],
            ]
        );
        
        if ($verification['link_valid']) {
            $this->info("✅ " . $verification['message']);
        } else {
            $this->error("❌ " . $verification['message']);
            
            if (!$verification['link_exists']) {
                $this->info("💡 Run: php artisan storage:link-auto");
            } elseif (!$verification['link_valid']) {
                $this->info("💡 Run: php artisan storage:link-auto --force");
            }
        }
    }
    
    /**
     * Test file operations
     */
    protected function testFileOperations(): void
    {
        $this->info("📝 Testing File Operations:");
        
        $testFile = 'test-' . time() . '.txt';
        $testContent = 'Storage test file created at ' . now();
        
        try {
            // Test write
            $this->info("Writing test file...");
            Storage::disk('public')->put($testFile, $testContent);
            $this->info("✅ File written successfully");
            
            // Test read
            $this->info("Reading test file...");
            $content = Storage::disk('public')->get($testFile);
            if ($content === $testContent) {
                $this->info("✅ File read successfully");
            } else {
                $this->error("❌ File content mismatch");
            }
            
            // Test URL generation
            $this->info("Generating file URL...");
            $url = StorageHelper::url($testFile);
            $this->info("✅ URL: {$url}");
            
            // Test file exists
            $this->info("Checking file existence...");
            if (StorageHelper::exists($testFile)) {
                $this->info("✅ File exists check passed");
            } else {
                $this->error("❌ File exists check failed");
            }
            
            // Test delete
            $this->info("Deleting test file...");
            if (StorageHelper::delete($testFile)) {
                $this->info("✅ File deleted successfully");
            } else {
                $this->error("❌ File deletion failed");
            }
            
            $this->newLine();
            $this->info("✅ All file operations completed successfully!");
            
        } catch (\Exception $e) {
            $this->error("❌ Error during file operations: " . $e->getMessage());
            
            // Cleanup
            if (Storage::disk('public')->exists($testFile)) {
                Storage::disk('public')->delete($testFile);
            }
        }
    }
}
