<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Policy Configuration
    |--------------------------------------------------------------------------
    |
    | Configure password requirements for user accounts
    |
    */

    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 12),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_special_chars' => env('PASSWORD_REQUIRE_SPECIAL_CHARS', true),
        'history_count' => env('PASSWORD_HISTORY_COUNT', 5),
        'expiry_days' => env('PASSWORD_EXPIRY_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configure session security settings
    |
    */

    'session' => [
        'strict_ip_check' => env('SESSION_STRICT_IP_CHECK', true),
        'regenerate_interval' => env('SESSION_REGENERATE_INTERVAL', 30), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Lockout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure account lockout after failed login attempts
    |
    */

    'lockout' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
        'lockout_duration' => env('LOGIN_LOCKOUT_DURATION', 30), // minutes
        'throttle_decay' => env('LOGIN_THROTTLE_DECAY', 1), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Configure 2FA settings
    |
    */

    '2fa' => [
        'enabled' => env('TWO_FACTOR_ENABLED', false),
        'issuer' => env('TWO_FACTOR_ISSUER', config('app.name')),
        'qr_code_size' => env('TWO_FACTOR_QR_CODE_SIZE', 200),
        'enforce_for_admins' => env('TWO_FACTOR_ENFORCE_ADMINS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure file upload security settings
    |
    */

    'upload' => [
        'max_size' => env('UPLOAD_MAX_SIZE', 10240), // KB
        'allowed_extensions' => explode(',', env('UPLOAD_ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx')),
        'scan_viruses' => env('UPLOAD_SCAN_VIRUSES', false),
        'quarantine_suspicious' => env('UPLOAD_QUARANTINE_SUSPICIOUS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Monitoring
    |--------------------------------------------------------------------------
    |
    | Configure security monitoring and alerting
    |
    */

    'monitoring' => [
        'alert_email' => env('SECURITY_ALERT_EMAIL'),
        'alert_slack' => env('SECURITY_ALERT_SLACK'),
        'alert_threshold' => env('SECURITY_ALERT_THRESHOLD', 10), // threats per hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Configure CSP reporting and monitoring
    |
    */

    'csp' => [
        'report_violations' => env('CSP_REPORT_VIOLATIONS', false),
        'report_only_mode' => env('CSP_REPORT_ONLY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Configure backup security settings
    |
    */

    'backup' => [
        'enabled' => env('BACKUP_ENABLED', true),
        'encrypt' => env('BACKUP_ENCRYPT', true),
        'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
    ],

];
