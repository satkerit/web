-- ========================================
-- SECURITY MONITORING - COMPLETE DEPLOYMENT SQL
-- ========================================
-- File ini menggabungkan semua SQL yang diperlukan:
-- 1. Membuat tabel security_logs
-- 2. Menambahkan kolom attack_count ke blocked_ips
-- 3. Menambahkan permissions
-- 4. Menambahkan menu Security Monitoring
-- 5. Assign permissions ke roles
-- ========================================

-- ========================================
-- PART 1: CREATE TABLES
-- ========================================

-- Pengecekan dan pembuatan tabel security_logs
SET @tableName = 'security_logs';

SET @dbName = DATABASE();

SET
    @tableExists = (
        SELECT COUNT(*)
        FROM information_schema.tables
        WHERE
            table_schema = @dbName
            AND table_name = @tableName
    );

-- Buat tabel security_logs jika belum ada
SET
    @sql = IF(
        @tableExists = 0,
        'CREATE TABLE security_logs (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        user_agent VARCHAR(500) NULL,
        request_method VARCHAR(10) NOT NULL,
        request_url VARCHAR(2048) NOT NULL,
        payload TEXT NULL,
        threat_type VARCHAR(50) NOT NULL,
        threat_level VARCHAR(20) NOT NULL DEFAULT "medium",
        matched_pattern TEXT NULL,
        raw_input TEXT NULL,
        user_id BIGINT UNSIGNED NULL,
        country_code VARCHAR(5) NULL,
        session_id VARCHAR(100) NULL,
        was_blocked BOOLEAN NOT NULL DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX security_logs_ip_address_index (ip_address),
        INDEX security_logs_threat_type_index (threat_type),
        INDEX security_logs_threat_level_index (threat_level),
        INDEX security_logs_created_at_index (created_at),
        INDEX security_logs_ip_address_created_at_index (ip_address, created_at)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;',
        'SELECT "✓ Tabel security_logs sudah ada" AS message'
    );

PREPARE stmt FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;

-- Pengecekan dan penambahan kolom attack_count ke tabel blocked_ips
SET @tableName = 'blocked_ips';

SET @columnName = 'attack_count';

SET
    @columnExists = (
        SELECT COUNT(*)
        FROM information_schema.columns
        WHERE
            table_schema = @dbName
            AND table_name = @tableName
            AND column_name = @columnName
    );

-- Tambahkan kolom attack_count jika belum ada
SET
    @sql = IF(
        @columnExists = 0,
        'ALTER TABLE blocked_ips ADD COLUMN attack_count INT NOT NULL DEFAULT 0 AFTER attempts;',
        'SELECT "✓ Kolom attack_count sudah ada" AS message'
    );

PREPARE stmt FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;

-- ========================================
-- PART 2: ADD PERMISSIONS
-- ========================================

-- Tambahkan Permission untuk Security Monitoring
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

-- Dapatkan ID permission yang baru dibuat
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

-- ========================================
-- PART 3: ASSIGN PERMISSIONS TO ROLES
-- ========================================

-- Dapatkan ID role super_admin dan admin
SET
    @superAdminRoleId = (
        SELECT id
        FROM roles
        WHERE
            name = 'super_admin'
        LIMIT 1
    );

SET
    @adminRoleId = (
        SELECT id
        FROM roles
        WHERE
            name = 'admin'
        LIMIT 1
    );

-- Assign permission ke super_admin (jika belum ada)
INSERT IGNORE INTO
    permission_role (
        permission_id,
        role_id,
        created_at,
        updated_at
    )
VALUES (
        @securityViewPermId,
        @superAdminRoleId,
        NOW(),
        NOW()
    ),
    (
        @securityManagePermId,
        @superAdminRoleId,
        NOW(),
        NOW()
    );

-- Assign permission ke admin (jika belum ada)
INSERT IGNORE INTO
    permission_role (
        permission_id,
        role_id,
        created_at,
        updated_at
    )
VALUES (
        @securityViewPermId,
        @adminRoleId,
        NOW(),
        NOW()
    ),
    (
        @securityManagePermId,
        @adminRoleId,
        NOW(),
        NOW()
    );

-- ========================================
-- PART 4: ADD MENU
-- ========================================

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

-- Dapatkan ID menu yang baru dibuat atau sudah ada
SET
    @securityMenuId = (
        SELECT id
        FROM admin_menus
        WHERE
            slug = 'security-monitor'
        LIMIT 1
    );

-- ========================================
-- PART 5: ASSIGN MENU TO ROLES
-- ========================================

-- Cek apakah sudah ada permission untuk menu ini (super_admin)
SET
    @menuPermExists = (
        SELECT COUNT(*)
        FROM admin_menu_permissions
        WHERE
            admin_menu_id = @securityMenuId
            AND role = 'super_admin'
    );

-- Insert menu permission untuk super_admin jika belum ada
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

-- Cek apakah sudah ada permission untuk menu ini (admin)
SET
    @menuPermExistsAdmin = (
        SELECT COUNT(*)
        FROM admin_menu_permissions
        WHERE
            admin_menu_id = @securityMenuId
            AND role = 'admin'
    );

-- Insert menu permission untuk admin jika belum ada
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
-- VERIFICATION & SUMMARY
-- ========================================

SELECT '========================================' AS '';

SELECT 'DEPLOYMENT SUMMARY' AS '';

SELECT '========================================' AS '';

SELECT '' AS '';

SELECT '✓ TABLES CREATED/VERIFIED:' AS status;

SELECT TABLE_NAME as 'Table', TABLE_ROWS as 'Rows', ROUND(
        (
            (DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024
        ), 2
    ) as 'Size (MB)'
FROM information_schema.TABLES
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN (
        'security_logs',
        'blocked_ips'
    )
ORDER BY TABLE_NAME;

SELECT '' AS '';

SELECT '✓ PERMISSIONS ADDED:' AS status;

SELECT id, name, display_name, `group`
FROM permissions
WHERE
    `group` = 'security'
ORDER BY name;

SELECT '' AS '';

SELECT '✓ PERMISSION-ROLE ASSIGNMENTS:' AS status;

SELECT
    p.name as permission_name,
    r.name as role_name,
    r.display_name as role_display_name
FROM
    permission_role pr
    JOIN permissions p ON pr.permission_id = p.id
    JOIN roles r ON pr.role_id = r.id
WHERE
    p.group = 'security'
ORDER BY r.name, p.name;

SELECT '' AS '';

SELECT '✓ MENU ADDED:' AS status;

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

SELECT '✓ MENU-ROLE ASSIGNMENTS:' AS status;

SELECT am.name AS menu_name, amp.role, am.route
FROM
    admin_menu_permissions amp
    JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE
    am.slug = 'security-monitor'
ORDER BY amp.role;

SELECT '' AS '';

SELECT '========================================' AS '';

SELECT '✅ DEPLOYMENT COMPLETED SUCCESSFULLY!' AS status;

SELECT '========================================' AS '';

SELECT '' AS '';

SELECT 'Next Steps:' AS '';

SELECT '1. Clear application cache: php artisan optimize:clear' AS '';

SELECT '2. Access dashboard: /admin/security-monitor' AS '';

SELECT '3. Test threat detection' AS '';

SELECT '4. Configure IP whitelist if needed' AS '';

SELECT '' AS '';