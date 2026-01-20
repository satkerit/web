<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageLinkProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-production 
                            {--force : Recreate the symbolic link if it already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create storage symbolic link for production environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = config('app.env');
        
        $this->info("Environment: {$environment}");
        $this->newLine();
        
        // Determine paths based on environment
        if ($environment === 'production') {
            $this->handleProductionLink();
        } else {
            $this->handleDevelopmentLink();
        }
        
        return 0;
    }
    
    /**
     * Handle production symbolic link creation
     */
    protected function handleProductionLink()
    {
        $storageUrl = env('STORAGE_URL');
        $storageRootPath = env('STORAGE_ROOT_PATH');
        
        if (!$storageUrl || !$storageRootPath) {
            $this->error('❌ Production storage not configured!');
            $this->newLine();
            $this->warn('Please set these variables in your .env file:');
            $this->line('STORAGE_URL=https://yourdomain.com/dev/storage');
            $this->line('STORAGE_ROOT_PATH=/home/username/public_html/dev/storage');
            $this->newLine();
            $this->info('Example for project structure:');
            $this->line('- Project: /home/username/laravel_project/');
            $this->line('- Public: /home/username/public_html/dev/');
            $this->line('- Storage: /home/username/public_html/dev/storage/');
            return 1;
        }
        
        $target = storage_path('app/public');
        $link = $storageRootPath;
        
        $this->info('Production Storage Configuration:');
        $this->line("Target (source): {$target}");
        $this->line("Link (destination): {$link}");
        $this->line("URL: {$storageUrl}");
        $this->newLine();
        
        // Check if target exists
        if (!File::exists($target)) {
            $this->error("❌ Target directory does not exist: {$target}");
            $this->info('Creating target directory...');
            File::makeDirectory($target, 0755, true);
            $this->info("✓ Target directory created");
        }
        
        // Check if link already exists
        if (File::exists($link)) {
            if ($this->option('force')) {
                $this->warn("Removing existing link/directory: {$link}");
                if (is_link($link)) {
                    unlink($link);
                } else {
                    File::deleteDirectory($link);
                }
            } else {
                $this->error("❌ Link already exists: {$link}");
                $this->info('Use --force to recreate the link');
                return 1;
            }
        }
        
        // Create parent directory if needed
        $parentDir = dirname($link);
        if (!File::exists($parentDir)) {
            $this->info("Creating parent directory: {$parentDir}");
            File::makeDirectory($parentDir, 0755, true);
        }
        
        // Create symbolic link
        try {
            symlink($target, $link);
            $this->info("✓ Symbolic link created successfully!");
            $this->newLine();
            $this->info('Storage is now accessible at: ' . $storageUrl);
        } catch (\Exception $e) {
            $this->error("❌ Failed to create symbolic link: " . $e->getMessage());
            return 1;
        }
        
        // Create subdirectories
        $this->createStorageDirectories($target);
    }
    
    /**
     * Handle development symbolic link creation
     */
    protected function handleDevelopmentLink()
    {
        $target = storage_path('app/public');
        $link = public_path('storage');
        
        $this->info('Development Storage Configuration:');
        $this->line("Target: {$target}");
        $this->line("Link: {$link}");
        $this->newLine();
        
        // Check if target exists
        if (!File::exists($target)) {
            $this->info('Creating target directory...');
            File::makeDirectory($target, 0755, true);
        }
        
        // Check if link already exists
        if (File::exists($link)) {
            if ($this->option('force')) {
                $this->warn("Removing existing link: {$link}");
                if (is_link($link)) {
                    unlink($link);
                } else {
                    File::deleteDirectory($link);
                }
            } else {
                $this->error("❌ Link already exists: {$link}");
                $this->info('Use --force to recreate the link');
                return 1;
            }
        }
        
        // Create symbolic link
        try {
            symlink($target, $link);
            $this->info("✓ Symbolic link created successfully!");
            $this->newLine();
            $this->info('Storage is now accessible at: ' . url('/storage'));
        } catch (\Exception $e) {
            $this->error("❌ Failed to create symbolic link: " . $e->getMessage());
            return 1;
        }
        
        // Create subdirectories
        $this->createStorageDirectories($target);
    }
    
    /**
     * Create storage subdirectories
     */
    protected function createStorageDirectories($basePath)
    {
        $directories = [
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
        
        $this->newLine();
        $this->info('Creating storage subdirectories...');
        
        foreach ($directories as $dir) {
            $path = $basePath . '/' . $dir;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->line("✓ Created: {$dir}");
            }
        }
        
        $this->newLine();
        $this->info('✓ All storage directories ready!');
    }
}
