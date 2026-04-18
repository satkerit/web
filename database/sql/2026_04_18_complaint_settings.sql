-- =============================================================================
-- Migration: Pengaturan Konfigurasi Pengaduan Nasabah
-- Tanggal   : 2026-04-18
-- Deskripsi : Membuat tabel complaint_settings beserta data permission dan
--             menu admin untuk fitur pengaturan pengaduan nasabah.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1. TABEL: complaint_settings
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `complaint_settings` (
    `id`                            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

    -- Notifikasi Email
    `admin_email`                   VARCHAR(255)    NULL        COMMENT 'Email penerima notifikasi pengaduan nasabah',
    `cc_emails`                     VARCHAR(255)    NULL        COMMENT 'CC email (pisahkan dengan koma)',
    `notify_on_new`                 TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Kirim notifikasi saat pengaduan baru masuk',
    `notify_on_status_change`       TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Kirim notifikasi saat status berubah',
    `send_confirmation_to_customer` TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Kirim konfirmasi ke nasabah',

    -- SLA & Batas Waktu
    `sla_days_low`                  INT UNSIGNED    NOT NULL    DEFAULT 14  COMMENT 'SLA hari untuk prioritas rendah',
    `sla_days_medium`               INT UNSIGNED    NOT NULL    DEFAULT 7   COMMENT 'SLA hari untuk prioritas sedang',
    `sla_days_high`                 INT UNSIGNED    NOT NULL    DEFAULT 3   COMMENT 'SLA hari untuk prioritas tinggi',

    -- Pengaturan Form
    `require_account_number`        TINYINT(1)      NOT NULL    DEFAULT 0   COMMENT 'Wajibkan nomor rekening',
    `require_phone`                 TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Wajibkan nomor telepon',
    `allow_attachments`             TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Izinkan lampiran file',
    `max_attachments`               INT UNSIGNED    NOT NULL    DEFAULT 5   COMMENT 'Maksimal jumlah lampiran',
    `max_file_size_mb`              INT UNSIGNED    NOT NULL    DEFAULT 5   COMMENT 'Ukuran maksimal file (MB)',
    `allowed_file_types`            VARCHAR(255)    NOT NULL    DEFAULT 'pdf,doc,docx,jpg,jpeg,png' COMMENT 'Tipe file yang diizinkan',

    -- Pengaturan Tiket
    `ticket_prefix`                 VARCHAR(255)    NOT NULL    DEFAULT 'ADU' COMMENT 'Prefix nomor tiket',
    `auto_assign_priority`          TINYINT(1)      NOT NULL    DEFAULT 1   COMMENT 'Otomatis tentukan prioritas',

    -- Teks & Konten
    `form_intro_text`               TEXT            NULL        COMMENT 'Teks pengantar form pengaduan',
    `success_message`               TEXT            NULL        COMMENT 'Pesan sukses setelah submit',
    `terms_text`                    TEXT            NULL        COMMENT 'Teks syarat & ketentuan',

    -- Kategori Aktif
    `active_categories`             JSON            NULL        COMMENT 'Kategori pengaduan yang aktif',

    `created_at`                    TIMESTAMP       NULL        DEFAULT NULL,
    `updated_at`                    TIMESTAMP       NULL        DEFAULT NULL,

    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- 2. DATA: permissions — tambah permission settings.complaints
--    Sesuaikan id jika berbeda di environment Anda
-- -----------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `display_name`, `group`, `created_at`, `updated_at`)
VALUES ('settings.complaints', 'Pengaturan Pengaduan Nasabah', 'settings', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `display_name` = VALUES(`display_name`),
    `group`        = VALUES(`group`),
    `updated_at`   = NOW();


-- -----------------------------------------------------------------------------
-- 3. DATA: admin_menus — tambah menu Pengaturan Pengaduan
-- -----------------------------------------------------------------------------
INSERT INTO `admin_menus` (`key`, `name`, `route`, `section`, `order`, `is_active`, `created_at`, `updated_at`)
VALUES ('complaint-settings', 'Pengaturan Pengaduan', 'admin.settings.complaint', 'Layanan', 32, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name`       = VALUES(`name`),
    `route`      = VALUES(`route`),
    `section`    = VALUES(`section`),
    `order`      = VALUES(`order`),
    `is_active`  = VALUES(`is_active`),
    `updated_at` = NOW();


-- -----------------------------------------------------------------------------
-- 4. DATA: admin_menu_permissions — akses menu untuk super_admin & admin
--    Menggunakan subquery agar tidak bergantung pada ID hardcode
-- -----------------------------------------------------------------------------
INSERT INTO `admin_menu_permissions` (`admin_menu_id`, `role_id`, `can_access`, `created_at`, `updated_at`)
SELECT
    (SELECT `id` FROM `admin_menus` WHERE `key` = 'complaint-settings'),
    `id`,
    1,
    NOW(),
    NOW()
FROM `roles`
WHERE `name` IN ('super_admin', 'admin')
ON DUPLICATE KEY UPDATE
    `can_access`  = 1,
    `updated_at`  = NOW();


-- -----------------------------------------------------------------------------
-- 5. DATA: role_permissions — assign permission ke super_admin & admin
-- -----------------------------------------------------------------------------
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT
    r.`id`,
    p.`id`,
    NOW(),
    NOW()
FROM `roles` r
CROSS JOIN `permissions` p
WHERE r.`name` IN ('super_admin', 'admin')
  AND p.`name` = 'settings.complaints'
ON DUPLICATE KEY UPDATE
    `updated_at` = NOW();


-- =============================================================================
-- SELESAI
-- Untuk rollback / undo, jalankan perintah berikut:
--
--   DELETE FROM `role_permissions`
--     WHERE `permission_id` = (SELECT `id` FROM `permissions` WHERE `name` = 'settings.complaints');
--
--   DELETE FROM `admin_menu_permissions`
--     WHERE `admin_menu_id` = (SELECT `id` FROM `admin_menus` WHERE `key` = 'complaint-settings');
--
--   DELETE FROM `admin_menus`   WHERE `key`  = 'complaint-settings';
--   DELETE FROM `permissions`   WHERE `name` = 'settings.complaints';
--   DROP TABLE IF EXISTS `complaint_settings`;
-- =============================================================================
