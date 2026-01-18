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
        // Auto-configure storage URL based on environment
        $this->configureStorageUrl();
        
        // Configure storage path for production
        $this->configureStoragePath();
    }

    /**
     * Configure storage URL based on environment
     */
    protected function configureStorageUrl(): void
    {
        $env = config('app.env');
        $appUrl = config('app.url');

        // Set storage URL based on environment
        if ($env === 'production') {
            // Production: Use APP_URL from .env
            $storageUrl = rtrim($appUrl, '/') . '/storage';
        } else {
            // Local/Development: Use APP_URL from .env
            $storageUrl = rtrim($appUrl, '/') . '/storage';
        }

        // Override storage URL if STORAGE_URL is set in .env
        if (env('STORAGE_URL')) {
            $storageUrl = env('STORAGE_URL');
        }

        // Set the storage URL
        config(['filesystems.disks.public.url' => $storageUrl]);

        // Force HTTPS in production if APP_URL uses https
        if ($env === 'production' && str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Configure storage path for production (when root is outside public_html)
     */
    protected function configureStoragePath(): void
    {
        $env = config('app.env');

        // In production, if storage symlink path is different
        if ($env === 'production') {
            // Check if custom storage path is defined
            $customStoragePath = env('STORAGE_PUBLIC_PATH');
            
            if ($customStoragePath && is_dir($customStoragePath)) {
                // Override storage public path
                config(['filesystems.disks.public.root' => $customStoragePath]);
            }
        }
    }
}

