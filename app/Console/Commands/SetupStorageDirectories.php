<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStorageDirectories extends Command
{
    protected $signature = 'storage:setup-directories {--force : Force creation even if directories exist}';
    protected $description = 'Setup required storage directories for production';

    protected $requiredDirectories = [
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

    public function handle()
    {
        $this->info('Setting up storage directories...');
        $this->newLine();

        $rootPath = config('filesystems.disks.public.root');
        $this->line('Root path: ' . $rootPath);
        $this->newLine();

        // Check if root exists
        if (!File::exists($rootPath)) {
            $this->warn('Root directory does not exist!');
            
            if ($this->confirm('Create root directory?', true)) {
                try {
                    File::makeDirectory($rootPath, 0775, true);
                    $this->info('✓ Root directory created');
                } catch (\Exception $e) {
                    $this->error('Failed to create root directory: ' . $e->getMessage());
                    return Command::FAILURE;
                }
            } else {
                $this->error('Cannot proceed without root directory');
                return Command::FAILURE;
            }
        }

        // Create subdirectories
        $created = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($this->requiredDirectories as $dir) {
            $path = $rootPath . '/' . $dir;
            
            if (File::exists($path) && !$this->option('force')) {
                $this->line("⊘ Skipped: {$dir} (already exists)");
                $skipped++;
                continue;
            }

            try {
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0775, true);
                }
                
                // Set permissions
                chmod($path, 0775);
                
                $this->info("✓ Created: {$dir}");
                $created++;
                
            } catch (\Exception $e) {
                $this->error("✗ Failed: {$dir} - " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info('=== Summary ===');
        $this->line("Created: {$created}");
        $this->line("Skipped: {$skipped}");
        
        if ($failed > 0) {
            $this->error("Failed: {$failed}");
        }

        $this->newLine();
        
        // Additional checks
        if (app()->environment('production')) {
            $this->warn('PRODUCTION ENVIRONMENT DETECTED');
            $this->newLine();
            $this->line('Please verify:');
            $this->line('1. Directory ownership is correct');
            $this->line('2. Web server can write to these directories');
            $this->line('3. STORAGE_URL in .env is correct');
            $this->newLine();
            $this->line('Run: php artisan storage:test');
        }

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
