<?php

/**
 * Production Storage Configuration
 * 
 * This file handles storage configuration for production environment
 * where the project is located outside public_html but storage needs
 * to be accessible via web.
 */

return [
    
    /*
    |--------------------------------------------------------------------------
    | Production Storage Path Configuration
    |--------------------------------------------------------------------------
    |
    | When the Laravel project is outside public_html (like in your case),
    | we need to configure storage paths properly for production.
    |
    | Structure:
    | - Project: /home/user/laravel_project/
    | - Public: /home/user/public_html/dev/
    | - Storage: /home/user/public_html/dev/storage/
    |
    */
    
    'production_paths' => [
        // Path to storage in public_html for production
        'public_storage_path' => env('STORAGE_PUBLIC_PATH', public_path('storage')),
        
        // URL for accessing storage files
        'storage_url' => env('STORAGE_URL', env('APP_URL') . '/storage'),
        
        // Symlink target (where files are actually stored)
        'storage_target' => storage_path('app/public'),
    ],
    
];