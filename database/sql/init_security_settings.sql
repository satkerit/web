-- =====================================================
-- INITIALIZE SECURITY SETTINGS & ADD MENU
-- Jalankan file ini untuk:
-- 1. Initialize security_settings (jika kosong)
-- 2. Menambahkan menu Security Settings
-- =====================================================

-- =====================================================
-- Step 1: Initialize Security Settings
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

-- Verify security settings
SELECT 'Security settings initialized successfully!' as status;
SELECT * FROM security_settings;

-- =====================================================
-- Step 2: Add Security Settings Menu
-- =====================================================

-- Insert menu Security Settings
INSERT INTO admin_menus (`key`, `name`, `route`, `section`, `order`, `created_at`, `updated_at`)
VALUES ('security-settings', 'Keamanan', 'admin.settings.security', 'Sistem', 43, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    `name` = 'Keamanan',
    `route` = 'admin.settings.security',
    `section` = 'Sistem',
    `order` = 43,
    `updated_at` = NOW();

-- Get the menu ID
SET @menu_id = (SELECT id FROM admin_menus WHERE `key` = 'security-settings' LIMIT 1);

-- Add permissions for all roles
-- Super Admin
INSERT INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'super_admin', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE can_access = 1, updated_at = NOW();

-- Admin
INSERT INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'admin', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE can_access = 1, updated_at = NOW();

-- Editor (no access)
INSERT INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'editor', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE can_access = 0, updated_at = NOW();

-- Update order untuk menu lainnya agar tidak bentrok
UPDATE admin_menus SET `order` = 44 WHERE `key` = 'email-settings';
UPDATE admin_menus SET `order` = 45 WHERE `key` = 'financing-config';
UPDATE admin_menus SET `order` = 46 WHERE `key` = 'audit-trails';
UPDATE admin_menus SET `order` = 47 WHERE `key` = 'visitor-stats';
UPDATE admin_menus SET `order` = 48 WHERE `key` = 'menu-permissions';
UPDATE admin_menus SET `order` = 49 WHERE `key` = 'roles';
UPDATE admin_menus SET `order` = 50 WHERE `key` = 'users';

-- Verify menu
SELECT 'Security Settings menu added successfully!' as status;
SELECT * FROM admin_menus WHERE `key` = 'security-settings';
SELECT amp.role, amp.can_access, am.name as menu_name 
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.key = 'security-settings';

-- =====================================================
-- DONE!
-- =====================================================

-- =====================================================
-- Step 3: Add Security Permission (for Role-based system)
-- =====================================================

-- Insert permission settings.security
INSERT INTO permissions (`name`, `display_name`, `group`, `description`, `created_at`, `updated_at`)
VALUES ('settings.security', 'Kelola Keamanan', 'settings', 'Mengelola pengaturan keamanan sistem', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    `display_name` = 'Kelola Keamanan',
    `group` = 'settings',
    `description` = 'Mengelola pengaturan keamanan sistem',
    `updated_at` = NOW();

-- Get permission ID
SET @permission_id = (SELECT id FROM permissions WHERE `name` = 'settings.security' LIMIT 1);

-- Add permission to Super Admin role (role_id = 1)
INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at)
SELECT 1, @permission_id, NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM role_permissions WHERE role_id = 1 AND permission_id = @permission_id
);

-- Add permission to Admin role (role_id = 2)
INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at)
SELECT 2, @permission_id, NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM role_permissions WHERE role_id = 2 AND permission_id = @permission_id
);

-- Verify permission
SELECT 'Security permission added successfully!' as status;
SELECT * FROM permissions WHERE `name` = 'settings.security';

-- =====================================================
-- DONE!
-- =====================================================
SELECT '✅ All done! Security settings initialized and menu added.' as final_status;
