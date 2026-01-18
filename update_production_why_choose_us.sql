-- Update Database Production untuk Fitur "Why Choose Us" (Keunggulan)
-- Tanggal: 18 Januari 2026

-- 1. Membuat Tabel 'why_choose_us'
CREATE TABLE IF NOT EXISTS `why_choose_us` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `color_theme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
    `sort_order` int(11) NOT NULL DEFAULT 0,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 2. Menambahkan Menu 'Keunggulan' ke tabel 'admin_menus'
INSERT INTO
    `admin_menus` (
        `key`,
        `name`,
        `route`,
        `icon`,
        `section`,
        `order`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
SELECT 'why-choose-us', 'Keunggulan', 'admin.why-choose-us.index', 'why-choose-us', 'Konten', 25, 1, NOW(), NOW()
WHERE
    NOT EXISTS (
        SELECT 1
        FROM `admin_menus`
        WHERE
            `key` = 'why-choose-us'
    );

-- 3. Menambahkan Hak Akses (Permissions) ke tabel 'admin_menu_permissions'
-- Mengambil ID menu yang baru saja dibuat/dipastikan ada (menggunakan variabel session user-defined)
SET
    @menu_id = (
        SELECT `id`
        FROM `admin_menus`
        WHERE
            `key` = 'why-choose-us'
        LIMIT 1
    );

-- Insert Permission untuk 'super_admin'
INSERT INTO
    `admin_menu_permissions` (
        `admin_menu_id`,
        `role`,
        `can_access`,
        `created_at`,
        `updated_at`
    )
SELECT @menu_id, 'super_admin', 1, NOW(), NOW()
WHERE
    @menu_id IS NOT NULL
    AND NOT EXISTS (
        SELECT 1
        FROM `admin_menu_permissions`
        WHERE
            `admin_menu_id` = @menu_id
            AND `role` = 'super_admin'
    );

-- Insert Permission untuk 'admin'
INSERT INTO
    `admin_menu_permissions` (
        `admin_menu_id`,
        `role`,
        `can_access`,
        `created_at`,
        `updated_at`
    )
SELECT @menu_id, 'admin', 1, NOW(), NOW()
WHERE
    @menu_id IS NOT NULL
    AND NOT EXISTS (
        SELECT 1
        FROM `admin_menu_permissions`
        WHERE
            `admin_menu_id` = @menu_id
            AND `role` = 'admin'
    );
