-- =====================================================
-- ADD SECURITY SETTINGS MENU
-- Jalankan file ini HANYA untuk menambahkan menu Security Settings
-- (Jika sudah jalankan init_security_settings.sql, tidak perlu file ini)
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

-- Verify
SELECT 'Security Settings menu added successfully!' as status;
SELECT * FROM admin_menus WHERE `key` = 'security-settings';
SELECT amp.role, amp.can_access, am.name as menu_name 
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.key = 'security-settings';
