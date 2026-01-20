<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageLinkAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-auto 
                            {--force : Recreate the symbolic link if it already exists}
                            {--mode= : Force specific mode (development or production)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create storage symbolic link automatically based on environment (development or production)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Determine storage mode
        $mode = $this->option('mode') ?? env('STORAGE_MODE', 'development');
        
        $this->info("🔧 Storage Link Auto Setup");
        $this->info("Mode: " . strtoupper($mode));
        $this->newLine();
        
        if ($mode === 'production') {
            return $this->setupProductionLink();
        } else {
            return $this->setupDevelopmentLink();
        }
    }
    
    /**
     * Setup storage link for development environment
     */
    protected function setupDevelopmentLink(): int
    {
        $this->info("📁 Setting up DEVELOPMENT storage link...");
        $this->newLine();
        
        $link = public_path('storage');
        $target = storage_path('app/public');
        
        return $this->createSymlink($link, $target, 'Development');
    }
    
    /**
     * Setup storage link for production environment
     */
    protected function setupProductionLink(): int
    {
        $this->info("🚀 Setting up PRODUCTION storage link...");
        $this->newLine();
        
        $publicPath = env('PRODUCTION_PUBLIC_PATH');
        
        if (!$publicPath) {
            $this->error("❌ PRODUCTION_PUBLIC_PATH not set in .env file!");
            $this->info("Please add: PRODUCTION_PUBLIC_PATH=/home/username/public_html");
            return self::FAILURE;
        }
        
        if (!is_dir($publicPath)) {
            $this->error("❌ Production public path does not exist: {$publicPath}");
            return self::FAILURE;
        }
        
        $link = $publicPath . '/storage';
        $target = storage_path('app/public');
        
        // Show paths
        $this->table(
            ['Type', 'Path'],
            [
                ['Project Root', base_path()],
                ['Public Folder', $publicPath],
                ['Storage Target', $target],
                ['Symlink Location', $link],
            ]
        );
        $this->newLine();
        
        return $this->createSymlink($link, $target, 'Production');
    }
    
    /**
     * Create symbolic link
     */
    protected function createSymlink(string $link, string $target, string $mode): int
    {
        // Check if target exists
        if (!is_dir($target)) {
            $this->error("❌ Storage target directory does not exist: {$target}");
            $this->info("Creating target directory...");
            
            if (!File::makeDirectory($target, 0755, true)) {
                $this->error("Failed to create target directory!");
                return self::FAILURE;
            }
            
            $this->info("✅ Target directory created");
        }
        
        // Check if link already exists
        if (file_exists($link) || is_link($link)) {
            if ($this->option('force')) {
                $this->warn("⚠️  Removing existing link...");
                
                if (is_link($link)) {
                    unlink($link);
                } else {
                    File::deleteDirectory($link);
                }
            } else {
                $this->warn("⚠️  Storage link already exists!");
                $this->info("Use --force to recreate the link");
                
                // Verify the link
                if (is_link($link) && readlink($link) === $target) {
                    $this->info("✅ Existing link is correct");
                    return self::SUCCESS;
                } else {
                    $this->error("❌ Existing link points to wrong location!");
                    $this->info("Run with --force to fix it");
                    return self::FAILURE;
                }
            }
        }
        
        // Create the symbolic link
        try {
            // Check if running on Windows
            if (PHP_OS_FAMILY === 'Windows') {
                // On Windows, use relative path for better compatibility
                $relativePath = $this->getRelativePath(dirname($link), $target);
                
                // Try to create symlink (requires admin privileges on Windows)
                if (@symlink($target, $link)) {
                    $success = true;
                } else {
                    // Fallback: create junction on Windows (doesn't require admin)
                    $this->warn("⚠️  Symlink requires admin privileges on Windows");
                    $this->info("Trying to create directory junction instead...");
                    
                    $command = sprintf('mklink /J "%s" "%s"', $link, $target);
                    exec($command, $output, $returnCode);
                    $success = $returnCode === 0;
                    
                    if (!$success) {
                        $this->error("❌ Failed to create junction. You may need to:");
                        $this->info("1. Run this command as Administrator, OR");
                        $this->info("2. Enable Developer Mode in Windows Settings");
                        return self::FAILURE;
                    }
                }
            } else {
                // Unix/Linux: standard symlink
                $success = symlink($target, $link);
            }
            
            if ($success) {
                $this->newLine();
                $this->info("✅ {$mode} storage link created successfully!");
                $this->newLine();
                $this->info("Link: {$link}");
                $this->info("Target: {$target}");
                $this->newLine();
                
                // Test the link
                if ($this->testStorageLink($link, $target)) {
                    $this->info("✅ Storage link is working correctly!");
                } else {
                    $this->warn("⚠️  Storage link created but verification failed");
                }
                
                return self::SUCCESS;
            } else {
                $this->error("❌ Failed to create symbolic link!");
                
                if (PHP_OS_FAMILY === 'Windows') {
                    $this->info("On Windows, you may need to:");
                    $this->info("1. Run as Administrator, OR");
                    $this->info("2. Enable Developer Mode in Windows Settings, OR");
                    $this->info("3. Use: php artisan storage:link (Laravel's built-in command)");
                } else {
                    $this->info("You may need to run this command with sudo or check permissions");
                }
                
                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error creating symbolic link: " . $e->getMessage());
            
            if (PHP_OS_FAMILY === 'Windows') {
                $this->newLine();
                $this->info("💡 Alternative: Use Laravel's built-in command:");
                $this->info("   php artisan storage:link");
            }
            
            return self::FAILURE;
        }
    }
    
    /**
     * Get relative path from one directory to another
     */
    protected function getRelativePath(string $from, string $to): string
    {
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);
        
        $from = explode('/', $from);
        $to = explode('/', $to);
        
        $relPath = $to;
        
        foreach ($from as $depth => $dir) {
            if ($dir === $to[$depth]) {
                array_shift($relPath);
            } else {
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        
        return implode('/', $relPath);
    }
    
    /**
     * Test if storage link is working
     */
    protected function testStorageLink(string $link, string $target): bool
    {
        if (!is_link($link)) {
            return false;
        }
        
        $linkTarget = readlink($link);
        return $linkTarget === $target;
    }
}
