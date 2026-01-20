<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TestStorageConfig extends Command
{
    protected $signature = 'storage:test';
    protected $description = 'Test storage configuration and verify production setup';

    public function handle()
    {
        $this->info('=== Storage Configuration Test ===');
        $this->newLine();

        // 1. Environment Check
        $this->info('1. Environment: ' . app()->environment());
        $this->newLine();

        // 2. Configuration Values
        $this->info('2. Configuration Values:');
        $publicDisk = config('filesystems.disks.public');
        
        $this->line('   Root Path: ' . $publicDisk['root']);
        $this->line('   URL: ' . $publicDisk['url']);
        $this->line('   Visibility: ' . $publicDisk['visibility']);
        $this->newLine();

        // 3. Environment Variables
        $this->info('3. Environment Variables:');
        $this->line('   STORAGE_URL: ' . env('STORAGE_URL', 'not set'));
        $this->line('   STORAGE_PUBLIC_PATH: ' . env('STORAGE_PUBLIC_PATH', 'not set'));
        $this->line('   APP_URL: ' . env('APP_URL'));
        $this->newLine();

        // 4. Directory Check
        $this->info('4. Directory Check:');
        $rootPath = $publicDisk['root'];
        
        if (File::exists($rootPath)) {
            $this->line('   ✓ Root directory exists: ' . $rootPath);
            
            // Check if writable
            if (File::isWritable($rootPath)) {
                $this->line('   ✓ Directory is writable');
            } else {
                $this->error('   ✗ Directory is NOT writable');
            }
            
            // Check permissions
            $perms = substr(sprintf('%o', fileperms($rootPath)), -4);
            $this->line('   Permissions: ' . $perms);
            
        } else {
            $this->error('   ✗ Root directory does NOT exist: ' . $rootPath);
            
            if ($this->confirm('Do you want to create it?', true)) {
                try {
                    File::makeDirectory($rootPath, 0775, true);
                    $this->info('   ✓ Directory created successfully');
                } catch (\Exception $e) {
                    $this->error('   ✗ Failed to create directory: ' . $e->getMessage());
                }
            }
        }
        $this->newLine();

        // 5. Subdirectories Check
        $this->info('5. Required Subdirectories:');
        $requiredDirs = [
            'auctions',
            'board-members',
            'company',
            'complaints',
            'hero-images',
            'hero-slides',
            'news',
            'offices',
            'products',
            'reports',
        ];

        foreach ($requiredDirs as $dir) {
            $path = $rootPath . '/' . $dir;
            if (File::exists($path)) {
                $this->line('   ✓ ' . $dir);
            } else {
                $this->warn('   ✗ ' . $dir . ' (missing)');
            }
        }
        $this->newLine();

        // 6. Write Test
        $this->info('6. Write Test:');
        try {
            $testFile = 'test-' . time() . '.txt';
            $testContent = 'Storage test at ' . now();
            
            Storage::disk('public')->put($testFile, $testContent);
            $this->line('   ✓ File written successfully: ' . $testFile);
            
            // Read test
            $content = Storage::disk('public')->get($testFile);
            if ($content === $testContent) {
                $this->line('   ✓ File read successfully');
            } else {
                $this->error('   ✗ File content mismatch');
            }
            
            // URL test
            $url = Storage::disk('public')->url($testFile);
            $this->line('   URL: ' . $url);
            
            // Delete test
            Storage::disk('public')->delete($testFile);
            $this->line('   ✓ File deleted successfully');
            
        } catch (\Exception $e) {
            $this->error('   ✗ Write test failed: ' . $e->getMessage());
        }
        $this->newLine();

        // 7. Disk Space
        $this->info('7. Disk Space:');
        if (File::exists($rootPath)) {
            $size = $this->getDirectorySize($rootPath);
            $this->line('   Storage size: ' . $this->formatBytes($size));
        }
        $this->newLine();

        // 8. Summary
        $this->info('=== Test Complete ===');
        
        if (app()->environment('production')) {
            $this->warn('Running in PRODUCTION mode');
            $this->line('Make sure STORAGE_PUBLIC_PATH is set correctly in .env');
        } else {
            $this->info('Running in ' . strtoupper(app()->environment()) . ' mode');
        }

        return Command::SUCCESS;
    }

    protected function getDirectorySize($path)
    {
        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
