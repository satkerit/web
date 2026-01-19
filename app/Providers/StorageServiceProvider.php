<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure storage URL for production
        if ($this->app->environment('production')) {
            $this->configureProductionStorage();
        }
    }
    
    /**
     * Configure storage for production environment
     */
    protected function configureProductionStorage(): void
    {
        // Override storage URL if custom URL is set
        if ($customUrl = config('storage-production.production_paths.storage_url')) {
            config(['filesystems.disks.public.url' => $customUrl]);
        }
        
        // Override storage path if custom path is set
        if ($customPath = config('storage-production.production_paths.public_storage_path')) {
            // This is handled by the symlink, no need to override the root path
        }
    }
}