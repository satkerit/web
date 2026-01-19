<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\StorageHelper;

class SetupProductionStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:setup-production 
                            {--public-path= : Path to public_html/dev directory}
                            {--force : Force recreate symlink}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup storage symlink for production environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up production storage...');
        
        // Get paths
        $publicPath = $this->option('public-path');
        $force = $this->option('force');
        
        if (!$publicPath) {
            $publicPath = $this->ask('Enter the path to public_html/dev directory', '/home/user/public_html/dev');
        }
        
        // Validate public path
        if (!is_dir($publicPath)) {
            $this->error("Directory does not exist: {$publicPath}");
            return 1;
        }
        
        $storagePath = $publicPath . '/storage';
        $targetPath = storage_path('app/public');
        
        $this->info("Public path: {$publicPath}");
        $this->info("Storage symlink: {$storagePath}");
        $this->info("Target path: {$targetPath}");
        
        // Create target directory if it doesn't exist
        if (!is_dir($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
            $this->info("Created target directory: {$targetPath}");
        }
        
        // Remove existing symlink if it exists
        if (is_link($storagePath)) {
            if ($force || $this->confirm('Storage symlink already exists. Remove it?')) {
                unlink($storagePath);
                $this->info('Removed existing symlink');
            } else {
                $this->info('Keeping existing symlink');
                return 0;
            }
        }
        
        // Create symlink
        if (symlink($targetPath, $storagePath)) {
            $this->info('✅ Storage symlink created successfully!');
            
            // Update .env file
            $this->updateEnvFile($publicPath);
            
            // Test the setup
            $this->testSetup($storagePath, $targetPath);
            
        } else {
            $this->error('❌ Failed to create storage symlink');
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Update .env file with production storage configuration
     */
    protected function updateEnvFile($publicPath)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            $this->warn('.env file not found, skipping configuration update');
            return;
        }
        
        $envContent = file_get_contents($envPath);
        
        // Determine the storage URL based on APP_URL
        preg_match('/APP_URL=(.+)/', $envContent, $matches);
        $appUrl = isset($matches[1]) ? trim($matches[1]) : 'https://dev.bprsbabel.id';
        $storageUrl = rtrim($appUrl, '/') . '/storage';
        
        // Add or update storage configuration
        $storageConfig = "\n# Production Storage Configuration\n";
        $storageConfig .= "STORAGE_URL={$storageUrl}\n";
        $storageConfig .= "STORAGE_PUBLIC_PATH={$publicPath}/storage\n";
        
        // Remove existing storage config if present
        $envContent = preg_replace('/\n# Production Storage Configuration\n.*?\n/s', '', $envContent);
        $envContent = preg_replace('/STORAGE_URL=.*?\n/', '', $envContent);
        $envContent = preg_replace('/STORAGE_PUBLIC_PATH=.*?\n/', '', $envContent);
        
        // Add new config at the end
        $envContent = rtrim($envContent) . $storageConfig;
        
        file_put_contents($envPath, $envContent);
        
        $this->info('✅ Updated .env file with storage configuration');
        $this->line("STORAGE_URL={$storageUrl}");
        $this->line("STORAGE_PUBLIC_PATH={$publicPath}/storage");
    }
    
    /**
     * Test the storage setup
     */
    protected function testSetup($storagePath, $targetPath)
    {
        $this->info('Testing storage setup...');
        
        // Test 1: Check if symlink is valid
        if (is_link($storagePath) && readlink($storagePath) === $targetPath) {
            $this->info('✅ Symlink is valid');
        } else {
            $this->error('❌ Symlink is invalid');
            return;
        }
        
        // Test 2: Create test file
        $testFile = $targetPath . '/test-storage-setup.txt';
        $testContent = 'Storage setup test - ' . date('Y-m-d H:i:s');
        
        if (file_put_contents($testFile, $testContent)) {
            $this->info('✅ Can write to storage directory');
            
            // Test 3: Check if file is accessible via symlink
            $symlinkTestFile = $storagePath . '/test-storage-setup.txt';
            if (file_exists($symlinkTestFile) && file_get_contents($symlinkTestFile) === $testContent) {
                $this->info('✅ File accessible via symlink');
                
                // Clean up test file
                unlink($testFile);
                $this->info('✅ Test completed successfully');
            } else {
                $this->error('❌ File not accessible via symlink');
            }
        } else {
            $this->error('❌ Cannot write to storage directory');
        }
    }
}