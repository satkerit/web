-- =====================================================
-- ADD SECURITY PERMISSION
-- Jalankan file ini untuk menambahkan permission settings.security
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

-- Verify
SELECT 'Security permission added successfully!' as status;
SELECT * FROM permissions WHERE `name` = 'settings.security';
SELECT rp.role_id, r.name as role_name, p.display_name as permission_name
FROM role_permissions rp
JOIN roles r ON rp.role_id = r.id
JOIN permissions p ON rp.permission_id = p.id
WHERE p.name = 'settings.security';
