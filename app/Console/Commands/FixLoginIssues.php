<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class FixLoginIssues extends Command
{
    protected $signature = 'fix:login';
    protected $description = 'Fix login issues by clearing caches and sessions';

    public function handle()
    {
        $this->info('🔧 Fixing login issues...');
        $this->newLine();

        // 1. Clear all caches
        $this->info('1. Clearing configuration cache...');
        Artisan::call('config:clear');
        $this->line('   ✓ Configuration cache cleared');

        $this->info('2. Clearing application cache...');
        Artisan::call('cache:clear');
        $this->line('   ✓ Application cache cleared');

        $this->info('3. Clearing route cache...');
        Artisan::call('route:clear');
        $this->line('   ✓ Route cache cleared');

        $this->info('4. Clearing view cache...');
        Artisan::call('view:clear');
        $this->line('   ✓ View cache cleared');

        // 2. Clear sessions
        $this->info('5. Clearing session files...');
        $sessionPath = storage_path('framework/sessions');
        if (File::exists($sessionPath)) {
            $files = File::files($sessionPath);
            $count = 0;
            foreach ($files as $file) {
                if ($file->getFilename() !== '.gitignore') {
                    File::delete($file);
                    $count++;
                }
            }
            $this->line("   ✓ Cleared {$count} session files");
        }

        // 3. Clear rate limiter cache
        $this->info('6. Clearing rate limiter cache...');
        try {
            Cache::flush();
            $this->line('   ✓ Rate limiter cache cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Could not clear cache: ' . $e->getMessage());
        }

        // 4. Cache config
        $this->info('7. Caching configuration...');
        Artisan::call('config:cache');
        $this->line('   ✓ Configuration cached');

        $this->newLine();
        $this->info('✅ Login issues fixed!');
        $this->newLine();
        
        $this->comment('Next steps:');
        $this->line('1. Clear your browser cache and cookies');
        $this->line('2. Try logging in again');
        $this->line('3. If still having issues, check: storage/logs/laravel.log');
        
        return Command::SUCCESS;
    }
}
