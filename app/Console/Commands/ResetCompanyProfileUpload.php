<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetCompanyProfileUpload extends Command
{
    protected $signature = 'reset:company-profile-upload';
    protected $description = 'Reset and fix all components for company profile upload';

    public function handle()
    {
        $this->info('🔄 Resetting Company Profile Upload System...');
        $this->newLine();
        
        // 1. Clear all caches
        $this->line('1. Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $this->info('   ✓ All caches cleared');
        
        // 2. Clear sessions
        $this->line('2. Clearing sessions...');
        try {
            Artisan::call('session:flush');
            $this->info('   ✓ Sessions cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Session flush failed (might not be available): ' . $e->getMessage());
        }
        
        // 3. Recreate storage link
        $this->line('3. Recreating storage link...');
        if (is_link('public/storage')) {
            unlink('public/storage');
        }
        Artisan::call('storage:link');
        $this->info('   ✓ Storage link recreated');
        
        // 4. Ensure directories exist
        $this->line('4. Creating storage directories...');
        $directories = [
            'company',
            'company/profile'
        ];
        
        foreach ($directories as $dir) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
                $this->line("   ✓ Created: {$dir}");
            } else {
                $this->line("   ✓ Exists: {$dir}");
            }
        }
        
        // 5. Update permissions
        $this->line('5. Updating role permissions...');
        try {
            Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
            $this->info('   ✓ Permissions updated');
        } catch (\Exception $e) {
            $this->error('   ✗ Permission update failed: ' . $e->getMessage());
        }
        
        // 6. Test storage write
        $this->line('6. Testing storage write...');
        try {
            $testFile = 'company/profile/.test-write';
            Storage::disk('public')->put($testFile, 'test');
            Storage::disk('public')->delete($testFile);
            $this->info('   ✓ Storage write test passed');
        } catch (\Exception $e) {
            $this->error('   ✗ Storage write test failed: ' . $e->getMessage());
        }
        
        // 7. Optimize for production
        $this->line('7. Optimizing application...');
        if (app()->environment('production')) {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $this->info('   ✓ Production optimizations applied');
        } else {
            $this->line('   ⚠ Skipping optimization (not in production)');
        }
        
        $this->newLine();
        $this->info('✅ Company Profile Upload System Reset Complete!');
        $this->newLine();
        
        $this->line('Next steps:');
        $this->line('1. Refresh your browser (Ctrl+F5)');
        $this->line('2. Login to admin panel again');
        $this->line('3. Try uploading company profile image');
        $this->line('4. If still having issues, check browser console (F12)');
        
        return 0;
    }
}