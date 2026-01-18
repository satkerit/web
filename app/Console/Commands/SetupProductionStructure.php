<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupProductionStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'production:setup 
                            {--public-path= : Path to public_html folder}
                            {--subdir= : Subdirectory in public_html (e.g., dev)}
                            {--check : Only check current setup without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Laravel for production with root outside public_html';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('  Production Structure Setup');
        $this->info('===========================================');
        $this->newLine();

        $checkOnly = $this->option('check');
        
        if ($checkOnly) {
            return $this->checkSetup();
        }

        // Get paths
        $laravelRoot = base_path();
        $publicPath = $this->option('public-path') ?: $this->ask('Enter path to public_html folder', '/home/user/public_html');
        $subdir = $this->option('subdir') ?: $this->ask('Enter subdirectory in public_html (leave empty if none)', 'dev');

        // Build full public path
        $fullPublicPath = $publicPath;
        if ($subdir) {
            $fullPublicPath = rtrim($publicPath, '/') . '/' . trim($subdir, '/');
        }

        if (!File::isDirectory($publicPath)) {
            $this->error("Directory not found: {$publicPath}");
            return 1;
        }

        $this->info("Laravel Root: {$laravelRoot}");
        $this->info("Public HTML: {$publicPath}");
        if ($subdir) {
            $this->info("Subdirectory: {$subdir}");
            $this->info("Full Path: {$fullPublicPath}");
        }
        $this->newLine();

        if (!$this->confirm('Continue with setup?', true)) {
            $this->warn('Setup cancelled.');
            return 0;
        }

        // Step 1: Copy public files
        $this->info('Step 1: Copying public files...');
        $this->copyPublicFiles($laravelRoot . '/public', $fullPublicPath);

        // Step 2: Update index.php
        $this->info('Step 2: Updating index.php paths...');
        $this->updateIndexPaths($fullPublicPath, $laravelRoot);

        // Step 3: Create storage symlink
        $this->info('Step 3: Creating storage symlink...');
        $this->createStorageLink($fullPublicPath, $laravelRoot);

        // Step 4: Set permissions
        $this->info('Step 4: Setting permissions...');
        $this->setPermissions($laravelRoot);

        // Step 5: Update .env
        $this->info('Step 5: Updating .env configuration...');
        $this->updateEnvFile($laravelRoot, $subdir);

        $this->newLine();
        $this->info('✓ Production setup completed successfully!');
        $this->newLine();

        $this->displayNextSteps($fullPublicPath, $subdir);

        return 0;
    }

    /**
     * Check current setup
     */
    protected function checkSetup()
    {
        $this->info('Checking current setup...');
        $this->newLine();

        // Basic info
        $checks = [
            'Laravel Root' => base_path(),
            'Public Path' => public_path(),
            'Storage Path' => storage_path('app/public'),
            'APP_ENV' => config('app.env'),
            'APP_URL' => config('app.url'),
            'Storage URL' => config('filesystems.disks.public.url'),
        ];

        $this->table(['Setting', 'Value'], collect($checks)->map(fn($v, $k) => [$k, $v])->toArray());

        $this->newLine();

        // Check storage link
        $storageLink = public_path('storage');
        if (File::exists($storageLink)) {
            if (is_link($storageLink)) {
                $target = readlink($storageLink);
                $this->info("✓ Storage symlink exists");
                $this->line("  Link: {$storageLink}");
                $this->line("  Target: {$target}");
                
                // Check if target exists
                if (File::isDirectory($target)) {
                    $this->info("  ✓ Target directory exists");
                } else {
                    $this->error("  ✗ Target directory not found!");
                }
            } else {
                $this->warn("⚠ Storage exists but is not a symlink: {$storageLink}");
            }
        } else {
            $this->error("✗ Storage symlink not found: {$storageLink}");
            $this->line("  Run: php artisan storage:link");
        }

        $this->newLine();

        // Check permissions
        $this->info('Checking permissions...');
        $paths = [
            storage_path(),
            storage_path('app/public'),
            base_path('bootstrap/cache'),
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path) ? '✓' : '✗';
                $this->line("  {$writable} {$path} ({$perms})");
            }
        }

        $this->newLine();

        // Check .env
        $this->info('Checking .env configuration...');
        $envVars = [
            'APP_ENV',
            'APP_DEBUG',
            'APP_URL',
            'STORAGE_URL',
            'STORAGE_PUBLIC_PATH',
        ];

        foreach ($envVars as $var) {
            $value = env($var, 'not set');
            $this->line("  {$var}={$value}");
        }

        return 0;
    }

    /**
     * Copy public files
     */
    protected function copyPublicFiles($source, $destination)
    {
        $files = File::allFiles($source);
        $bar = $this->output->createProgressBar(count($files));

        foreach ($files as $file) {
            $relativePath = str_replace($source, '', $file->getPathname());
            $destPath = $destination . $relativePath;
            
            File::ensureDirectoryExists(dirname($destPath));
            File::copy($file->getPathname(), $destPath);
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✓ Public files copied');
    }

    /**
     * Update index.php paths
     */
    protected function updateIndexPaths($publicPath, $laravelRoot)
    {
        $indexPath = $publicPath . '/index.php';
        
        if (!File::exists($indexPath)) {
            $this->error("index.php not found: {$indexPath}");
            return;
        }

        $content = File::get($indexPath);

        // Update paths to point to Laravel root
        $content = str_replace(
            "__DIR__.'/../storage/framework/maintenance.php'",
            "'{$laravelRoot}/storage/framework/maintenance.php'",
            $content
        );

        $content = str_replace(
            "__DIR__.'/../vendor/autoload.php'",
            "'{$laravelRoot}/vendor/autoload.php'",
            $content
        );

        $content = str_replace(
            "__DIR__.'/../bootstrap/app.php'",
            "'{$laravelRoot}/bootstrap/app.php'",
            $content
        );

        File::put($indexPath, $content);
        $this->info('✓ index.php updated');
    }

    /**
     * Create storage symlink
     */
    protected function createStorageLink($publicPath, $laravelRoot)
    {
        $linkPath = $publicPath . '/storage';
        $targetPath = $laravelRoot . '/storage/app/public';

        // Remove existing link/directory
        if (File::exists($linkPath)) {
            if (is_link($linkPath)) {
                unlink($linkPath);
            } else {
                File::deleteDirectory($linkPath);
            }
        }

        // Create symlink
        symlink($targetPath, $linkPath);
        $this->info("✓ Storage symlink created: {$linkPath} -> {$targetPath}");
    }

    /**
     * Set permissions
     */
    protected function setPermissions($laravelRoot)
    {
        $paths = [
            $laravelRoot . '/storage',
            $laravelRoot . '/bootstrap/cache',
        ];

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                chmod($path, 0755);
                $this->info("✓ Permissions set for: {$path}");
            }
        }
    }

    /**
     * Update .env file
     */
    protected function updateEnvFile($laravelRoot, $subdir = null)
    {
        $envPath = $laravelRoot . '/.env';
        
        if (!File::exists($envPath)) {
            $this->warn('.env file not found, skipping...');
            return;
        }

        $content = File::get($envPath);

        // Add STORAGE_PUBLIC_PATH if not exists
        if (!str_contains($content, 'STORAGE_PUBLIC_PATH')) {
            $storagePath = $laravelRoot . '/storage/app/public';
            $content .= "\n# Production Storage Path\nSTORAGE_PUBLIC_PATH={$storagePath}\n";
        }

        // Add PRODUCTION_SUBDIR if subdirectory is used
        if ($subdir) {
            if (!str_contains($content, 'PRODUCTION_SUBDIR')) {
                $content .= "\n# Production Subdirectory\nPRODUCTION_SUBDIR={$subdir}\n";
            } else {
                $content = preg_replace('/^PRODUCTION_SUBDIR=.*/m', "PRODUCTION_SUBDIR={$subdir}", $content);
            }
        }

        File::put($envPath, $content);
        $this->info('✓ .env updated');
    }

    /**
     * Display next steps
     */
    protected function displayNextSteps($publicPath, $subdir = null)
    {
        $this->warn('Next Steps:');
        $this->line('1. Update your web server document root to: ' . $publicPath);
        $this->line('2. Update .env file:');
        $this->line('   - APP_ENV=production');
        $this->line('   - APP_DEBUG=false');
        $this->line('   - APP_URL=https://yourdomain.com');
        if ($subdir) {
            $this->line('   - PRODUCTION_SUBDIR=' . $subdir);
        }
        $this->line('3. Run: php artisan config:cache');
        $this->line('4. Run: php artisan route:cache');
        $this->line('5. Run: php artisan view:cache');
        $this->newLine();
        
        if ($subdir) {
            $this->info("Storage URL will be: https://yourdomain.com/{$subdir}/storage");
        } else {
            $this->info('Storage URL will be: https://yourdomain.com/storage');
        }
        
        $this->newLine();
        $this->info('For more information, see DEPLOYMENT.md');
    }
}
