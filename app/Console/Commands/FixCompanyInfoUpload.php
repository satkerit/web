<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FixCompanyInfoUpload extends Command
{
    protected $signature = 'fix:company-info-upload';
    protected $description = 'Fix company info image upload issues';

    public function handle()
    {
        $this->info('🔧 Fixing Company Info Upload Issues...');
        
        // 1. Check and create storage directories
        $this->info('📁 Checking storage directories...');
        $this->ensureStorageDirectories();
        
        // 2. Check storage link
        $this->info('🔗 Checking storage link...');
        $this->ensureStorageLink();
        
        // 3. Check permissions
        $this->info('🔐 Checking permissions...');
        $this->checkPermissions();
        
        // 4. Test storage functionality
        $this->info('🧪 Testing storage functionality...');
        $this->testStorage();
        
        // 5. Clear caches
        $this->info('🧹 Clearing caches...');
        $this->clearCaches();
        
        $this->info('✅ Company Info upload fix completed!');
        
        return 0;
    }
    
    private function ensureStorageDirectories()
    {
        $directories = [
            'company',
            'company/profile',
            'news',
            'products',
            'offices',
            'hero-slides',
        ];
        
        foreach ($directories as $dir) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
                $this->line("  ✅ Created directory: storage/app/public/{$dir}");
            } else {
                $this->line("  ✓ Directory exists: storage/app/public/{$dir}");
            }
        }
    }
    
    private function ensureStorageLink()
    {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        if (is_link($linkPath)) {
            $this->line('  ✓ Storage link exists');
        } elseif (is_dir($linkPath)) {
            $this->line('  ✓ Storage directory exists (not symlinked)');
        } else {
            // Create the link
            if (File::link($targetPath, $linkPath)) {
                $this->line('  ✅ Created storage link');
            } else {
                $this->error('  ❌ Failed to create storage link');
                $this->line('  💡 Try running: php artisan storage:link');
            }
        }
    }
    
    private function checkPermissions()
    {
        $storagePath = storage_path('app/public');
        $publicStoragePath = public_path('storage');
        
        // Check if storage directory is writable
        if (is_writable($storagePath)) {
            $this->line('  ✓ Storage directory is writable');
        } else {
            $this->error('  ❌ Storage directory is not writable: ' . $storagePath);
        }
        
        // Check if public storage is accessible
        if (file_exists($publicStoragePath)) {
            $this->line('  ✓ Public storage path exists');
        } else {
            $this->error('  ❌ Public storage path does not exist: ' . $publicStoragePath);
        }
    }
    
    private function testStorage()
    {
        try {
            // Test creating a file
            $testContent = 'test-' . time();
            $testFile = 'test-upload.txt';
            
            Storage::disk('public')->put($testFile, $testContent);
            
            if (Storage::disk('public')->exists($testFile)) {
                $this->line('  ✅ Storage write test passed');
                
                // Test reading
                $content = Storage::disk('public')->get($testFile);
                if ($content === $testContent) {
                    $this->line('  ✅ Storage read test passed');
                } else {
                    $this->error('  ❌ Storage read test failed');
                }
                
                // Test URL generation
                $url = Storage::disk('public')->url($testFile);
                $this->line('  ✓ Storage URL: ' . $url);
                
                // Clean up
                Storage::disk('public')->delete($testFile);
                $this->line('  ✅ Storage cleanup completed');
                
            } else {
                $this->error('  ❌ Storage write test failed');
            }
            
        } catch (\Exception $e) {
            $this->error('  ❌ Storage test failed: ' . $e->getMessage());
        }
    }
    
    private function clearCaches()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');
    }
}