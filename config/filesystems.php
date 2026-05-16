<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => false,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            // Root path: where files are physically stored
            // Always use storage/app/public (symlink will handle the rest)
            'root' => storage_path('app/public'),

            // URL: how to access files via web
            // Automatically configured based on STORAGE_URL env variable
            'url' => env('STORAGE_URL', env('APP_URL') . '/storage'),

            'visibility' => 'public',
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    | This configuration is dynamically adjusted based on STORAGE_MODE:
    | - development: public/storage -> storage/app/public
    | - production: public_html/storage -> app/storage/app/public
    |
    */

    'links' => (function () {
        $storageMode = env('STORAGE_MODE', 'development');

        if ($storageMode === 'production') {
            // Production: link from public_html to app/storage/app/public
            $publicPath = env('PRODUCTION_PUBLIC_PATH');

            if ($publicPath && is_dir($publicPath)) {
                return [
                    $publicPath . '/storage' => storage_path('app/public'),
                ];
            }
        }

        // Development: standard Laravel structure
        return [
            public_path('storage') => storage_path('app/public'),
        ];
    })(),

];
