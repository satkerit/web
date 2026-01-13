<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configure rate limits for different types of requests to prevent
    | DDoS attacks and abuse.
    |
    */

    'rate_limits' => [
        // General web requests
        'web' => [
            'max_attempts' => env('RATE_LIMIT_WEB', 120),
            'decay_minutes' => 1,
        ],

        // Admin panel requests
        'admin' => [
            'max_attempts' => env('RATE_LIMIT_ADMIN', 100),
            'decay_minutes' => 1,
        ],

        // Login attempts
        'login' => [
            'max_attempts' => env('RATE_LIMIT_LOGIN', 5),
            'decay_minutes' => 5,
        ],

        // Password reset requests
        'password_reset' => [
            'max_attempts' => env('RATE_LIMIT_PASSWORD_RESET', 3),
            'decay_minutes' => 1,
        ],

        // File downloads
        'download' => [
            'max_attempts' => env('RATE_LIMIT_DOWNLOAD', 30),
            'decay_minutes' => 1,
        ],

        // API requests (if any)
        'api' => [
            'max_attempts' => env('RATE_LIMIT_API', 60),
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Blocking Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic IP blocking for suspicious activity.
    |
    */

    'ip_blocking' => [
        // Number of suspicious requests before blocking
        'threshold' => env('SECURITY_BLOCK_THRESHOLD', 10),

        // How long to block IP (in hours)
        'block_duration' => env('SECURITY_BLOCK_DURATION', 24),

        // Whitelist IPs that should never be blocked
        'whitelist' => array_filter(explode(',', env('SECURITY_IP_WHITELIST', ''))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Idle Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure idle timeout for user sessions. Users will be automatically
    | logged out after being inactive for the specified time.
    |
    */

    'idle_timeout' => env('SESSION_IDLE_TIMEOUT', 30), // minutes

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure security headers for responses.
    |
    */

    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Additional session security settings.
    |
    */

    'session' => [
        // Regenerate session ID on login
        'regenerate_on_login' => true,

        // Maximum session lifetime (minutes)
        'max_lifetime' => env('SESSION_LIFETIME', 120),

        // Idle timeout (minutes) - logout after inactivity
        'idle_timeout' => env('SESSION_IDLE_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | Password requirements for user accounts.
    |
    */

    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special' => false,
    ],
];
