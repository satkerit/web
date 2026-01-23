-- =====================================================
-- INITIALIZE SECURITY SETTINGS
-- Jalankan file ini jika tabel security_settings kosong
-- =====================================================

-- Check if security_settings is empty
SELECT COUNT(*) as count FROM security_settings;

-- Insert default settings if empty
INSERT INTO security_settings (
    rate_limit_web,
    rate_limit_admin,
    rate_limit_login,
    rate_limit_password_reset,
    rate_limit_download,
    block_threshold,
    block_duration_hours,
    ip_whitelist,
    ip_blacklist,
    enable_suspicious_blocking,
    enable_rate_limiting,
    log_security_events,
    created_at,
    updated_at
)
SELECT 
    120,  -- rate_limit_web
    100,  -- rate_limit_admin
    5,    -- rate_limit_login
    3,    -- rate_limit_password_reset
    30,   -- rate_limit_download
    10,   -- block_threshold
    24,   -- block_duration_hours
    NULL, -- ip_whitelist (kosong, isi manual via admin)
    NULL, -- ip_blacklist (kosong, isi manual via admin)
    1,    -- enable_suspicious_blocking
    1,    -- enable_rate_limiting
    1,    -- log_security_events
    NOW(),
    NOW()
WHERE NOT EXISTS (SELECT 1 FROM security_settings LIMIT 1);

-- Verify
SELECT 'Security settings initialized successfully!' as status;
SELECT * FROM security_settings;
