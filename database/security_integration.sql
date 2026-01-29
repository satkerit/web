-- ========================================
-- SQL untuk Integrasi Security Monitoring
-- ========================================
-- File ini menambahkan:
-- 1. Permission untuk Security Monitoring
-- 2. Menu Security Monitoring di sidebar admin
-- 3. Assign permission ke role super_admin
-- ========================================

-- 1. Tambahkan Permission untuk Security Monitoring
INSERT INTO
    permissions (
        name,
        display_name,
        `group`,
        created_at,
        updated_at
    )
VALUES (
        'security.view',
        'Lihat Security Logs',
        'security',
        NOW(),
        NOW()
    ),
    (
        'security.manage',
        'Kelola Security (Block/Unblock)',
        'security',
        NOW(),
        NOW()
    )
ON DUPLICATE KEY UPDATE
    display_name = VALUES(display_name),
    `group` = VALUES(`group`),
    updated_at = NOW();

-- 2. Dapatkan ID permission yang baru dibuat
SET
    @securityViewPermId = (
        SELECT id
        FROM permissions
        WHERE
            name = 'security.view'
        LIMIT 1
    );

SET
    @securityManagePermId = (
        SELECT id
        FROM permissions
        WHERE
            name = 'security.manage'
        LIMIT 1
    );

-- 3. Dapatkan ID role super_admin
SET
    @superAdminRoleId = (
        SELECT id
        FROM roles
        WHERE
            name = 'super_admin'
        LIMIT 1
    );

-- 4. Assign permission ke super_admin (jika belum ada)
-- FIXED: Menggunakan tabel 'role_permissions' dengan urutan (role_id, permission_id)
INSERT IGNORE INTO
    role_permissions (
        role_id,
        permission_id,
        created_at,
        updated_at
    )
VALUES (
        @superAdminRoleId,
        @securityViewPermId,
        NOW(),
        NOW()
    ),
    (
        @superAdminRoleId,
        @securityManagePermId,
        NOW(),
        NOW()
    );

-- 5. Tambahkan menu Security Monitoring ke admin_menus
-- Cek apakah menu sudah ada
SET
    @menuExists = (
        SELECT COUNT(*)
        FROM admin_menus
        WHERE
            slug = 'security-monitor'
    );

-- Insert menu jika belum ada
INSERT INTO
    admin_menus (
        name,
        slug,
        route,
        icon,
        section,
        `order`,
        is_active,
        created_at,
        updated_at
    )
SELECT 'Security Monitoring', 'security-monitor', 'admin.security-monitor.index', 'security-monitor', 'Keamanan', 90, 1, NOW(), NOW()
WHERE
    @menuExists = 0;

-- 6. Dapatkan ID menu yang baru dibuat atau sudah ada
SET
    @securityMenuId = (
        SELECT id
        FROM admin_menus
        WHERE
            slug = 'security-monitor'
        LIMIT 1
    );

-- 7. Assign menu ke super_admin role via admin_menu_permissions
-- Cek apakah sudah ada permission untuk menu ini
SET
    @menuPermExists = (
        SELECT COUNT(*)
        FROM admin_menu_permissions
        WHERE
            admin_menu_id = @securityMenuId
            AND role = 'super_admin'
    );

-- Insert menu permission jika belum ada
INSERT INTO
    admin_menu_permissions (
        admin_menu_id,
        role,
        created_at,
        updated_at
    )
SELECT @securityMenuId, 'super_admin', NOW(), NOW()
WHERE
    @menuPermExists = 0;

-- Optional: Assign juga ke role 'admin' jika Anda ingin admin biasa bisa akses
SET
    @menuPermExistsAdmin = (
        SELECT COUNT(*)
        FROM admin_menu_permissions
        WHERE
            admin_menu_id = @securityMenuId
            AND role = 'admin'
    );

INSERT INTO
    admin_menu_permissions (
        admin_menu_id,
        role,
        created_at,
        updated_at
    )
SELECT @securityMenuId, 'admin', NOW(), NOW()
WHERE
    @menuPermExistsAdmin = 0;

-- ========================================
-- Verifikasi hasil
-- ========================================
SELECT 'Permissions berhasil ditambahkan:' AS status;

SELECT id, name, display_name, `group`
FROM permissions
WHERE
    `group` = 'security';

SELECT '' AS '';

SELECT 'Menu berhasil ditambahkan:' AS status;

SELECT
    id,
    name,
    slug,
    route,
    section,
    `order`,
    is_active
FROM admin_menus
WHERE
    slug = 'security-monitor';

SELECT '' AS '';

SELECT 'Menu permissions berhasil ditambahkan:' AS status;

SELECT amp.id, am.name AS menu_name, amp.role
FROM
    admin_menu_permissions amp
    JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE
    am.slug = 'security-monitor';

SELECT '' AS '';

SELECT '✓ Integrasi Security Monitoring selesai!' AS status;