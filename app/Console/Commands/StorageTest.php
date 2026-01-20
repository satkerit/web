<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\StorageHelper;

class StorageTest extends Command
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
    protected $description = 'Test storage configuration and accessibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('   STORAGE CONFIGURATION TEST');
        $this->info('===========================================');
        $this->newLine();
        
        // Display configuration
        $this->displayConfiguration();
        $this->newLine();
        
        // Test storage paths
        $this->testStoragePaths();
        $this->newLine();
        
        // Test file operations
        $this->testFileOperations();
        $this->newLine();
        
        // Test URL generation
        $this->testUrlGeneration();
        $this->newLine();
        
        $this->info('===========================================');
        $this->info('   TEST COMPLETED');
        $this->info('===========================================');
        
        return 0;
    }
    
    /**
     * Display current configuration
     */
    protected function displayConfiguration()
    {
        $this->info('📋 Current Configuration:');
        $this->line('Environment: ' . config('app.env'));
        $this->line('App URL: ' . config('app.url'));
        $this->line('Storage Root: ' . config('filesystems.disks.public.root'));
        $this->line('Storage URL: ' . config('filesystems.disks.public.url'));
        
        if (config('app.env') === 'production') {
            $this->line('Storage Root Path (ENV): ' . env('STORAGE_ROOT_PATH', 'not set'));
            $this->line('Storage URL (ENV): ' . env('STORAGE_URL', 'not set'));
        }
    }
    
    /**
     * Test storage paths
     */
    protected function testStoragePaths()
    {
        $this->info('📁 Testing Storage Paths:');
        
        $storagePath = config('filesystems.disks.public.root');
        
        // Check if storage path exists
        if (File::exists($storagePath)) {
            $this->line("✓ Storage path exists: {$storagePath}");
        } else {
            $this->error("✗ Storage path does not exist: {$storagePath}");
        }
        
        // Check if storage is writable
        if (File::isWritable($storagePath)) {
            $this->line("✓ Storage path is writable");
        } else {
            $this->error("✗ Storage path is not writable");
        }
        
        // Check subdirectories
        $directories = [
            'auctions', 'board-members', 'company', 'complaints',
            'hero-images', 'hero-slides', 'news', 'offices',
            'products', 'reports'
        ];
        
        $this->newLine();
        $this->info('Checking subdirectories:');
        foreach ($directories as $dir) {
            $path = $storagePath . '/' . $dir;
            if (File::exists($path)) {
                $this->line("  ✓ {$dir}");
            } else {
                $this->warn("  ✗ {$dir} (missing)");
            }
        }
    }
    
    /**
     * Test file operations
     */
    protected function testFileOperations()
    {
        $this->info('🔧 Testing File Operations:');
        
        $testFile = 'test-' . time() . '.txt';
        $testContent = 'Storage test file created at ' . now();
        
        try {
            // Test write
            Storage::disk('public')->put($testFile, $testContent);
            $this->line("✓ File write successful");
            
            // Test read
            $content = Storage::disk('public')->get($testFile);
            if ($content === $testContent) {
                $this->line("✓ File read successful");
            } else {
                $this->error("✗ File read failed - content mismatch");
            }
            
            // Test exists
            if (Storage::disk('public')->exists($testFile)) {
                $this->line("✓ File exists check successful");
            } else {
                $this->error("✗ File exists check failed");
            }
            
            // Test delete
            Storage::disk('public')->delete($testFile);
            if (!Storage::disk('public')->exists($testFile)) {
                $this->line("✓ File delete successful");
            } else {
                $this->error("✗ File delete failed");
            }
            
        } catch (\Exception $e) {
            $this->error("✗ File operation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Test URL generation
     */
    protected function testUrlGeneration()
    {
        $this->info('🔗 Testing URL Generation:');
        
        $testPaths = [
            'news/test-image.jpg',
            'products/product-1.jpg',
            'hero-slides/slide-1.jpg',
        ];
        
        foreach ($testPaths as $path) {
            $url = StorageHelper::url($path);
            $this->line("Path: {$path}");
            $this->line("URL:  {$url}");
            $this->newLine();
        }
        
        // Test with actual files
        $this->info('Testing with actual files:');
        $files = Storage::disk('public')->files('news');
        
        if (count($files) > 0) {
            $sampleFile = $files[0];
            $url = StorageHelper::url($sampleFile);
            $this->line("Sample file: {$sampleFile}");
            $this->line("Generated URL: {$url}");
            $this->line("File exists: " . (StorageHelper::exists($sampleFile) ? 'Yes' : 'No'));
        } else {
            $this->warn('No files found in news directory for testing');
        }
    }
}
