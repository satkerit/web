-- ============================================
-- COMPLETE SQL - Why Choose Us System
-- ============================================
-- Database: cms_baru (atau sesuai nama database Anda)
-- ============================================

-- ============================================
-- 1. TAMBAH KOLOM background_image ke why_choose_us
-- ============================================

-- Check apakah kolom sudah ada
SELECT 
    COLUMN_NAME 
FROM 
    INFORMATION_SCHEMA.COLUMNS
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'why_choose_us'
    AND COLUMN_NAME = 'background_image';

-- Jika hasil kosong, jalankan query ini:
ALTER TABLE `why_choose_us` 
ADD COLUMN `background_image` VARCHAR(255) NULL 
COMMENT 'Path to background image file' 
AFTER `icon`;

-- Verifikasi
DESCRIBE `why_choose_us`;


-- ============================================
-- 2. CREATE TABLE why_choose_us_settings
-- ============================================

-- Check apakah tabel sudah ada
SHOW TABLES LIKE 'why_choose_us_settings';

-- Jika tidak ada, create table:
CREATE TABLE `why_choose_us_settings` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `section_title` VARCHAR(255) NOT NULL DEFAULT 'Mengapa Memilih Kami',
    `section_subtitle` TEXT NULL,
    `section_image` VARCHAR(255) NULL COMMENT 'Main section image',
    `badge_text` VARCHAR(255) NULL COMMENT 'e.g., 100% Syariah Compliant',
    `badge_icon` VARCHAR(255) NULL COMMENT 'Badge icon',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verifikasi
DESCRIBE `why_choose_us_settings`;


-- ============================================
-- 3. INSERT DEFAULT SETTINGS
-- ============================================

-- Insert default settings (hanya jika tabel kosong)
INSERT INTO `why_choose_us_settings` (
    `id`,
    `section_title`,
    `section_subtitle`,
    `badge_text`,
    `is_active`,
    `created_at`,
    `updated_at`
) VALUES (
    1,
    'Mengapa Memilih Kami',
    'Kami memberikan layanan terbaik dengan standar syariah yang terpercaya',
    '100% Syariah Compliant',
    1,
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    `updated_at` = NOW();


-- ============================================
-- 4. VERIFIKASI SEMUA TABEL
-- ============================================

-- Check struktur why_choose_us
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM 
    INFORMATION_SCHEMA.COLUMNS
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'why_choose_us'
ORDER BY 
    ORDINAL_POSITION;

-- Check struktur why_choose_us_settings
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM 
    INFORMATION_SCHEMA.COLUMNS
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'why_choose_us_settings'
ORDER BY 
    ORDINAL_POSITION;


-- ============================================
-- 5. LIHAT DATA
-- ============================================

-- Lihat semua items
SELECT 
    id,
    title,
    CASE WHEN icon IS NOT NULL THEN '✓' ELSE '✗' END AS has_icon,
    CASE WHEN background_image IS NOT NULL THEN '✓' ELSE '✗' END AS has_bg,
    color_theme,
    sort_order,
    CASE WHEN is_active = 1 THEN '✓ Aktif' ELSE '✗ Nonaktif' END AS status
FROM 
    `why_choose_us`
ORDER BY 
    sort_order ASC;

-- Lihat settings
SELECT 
    id,
    section_title,
    section_subtitle,
    CASE WHEN section_image IS NOT NULL THEN '✓' ELSE '✗' END AS has_section_image,
    badge_text,
    CASE WHEN badge_icon IS NOT NULL THEN '✓' ELSE '✗' END AS has_badge_icon,
    CASE WHEN is_active = 1 THEN '✓ Aktif' ELSE '✗ Nonaktif' END AS status
FROM 
    `why_choose_us_settings`;


-- ============================================
-- 6. CONTOH INSERT DATA
-- ============================================

-- Insert item dengan background image
INSERT INTO `why_choose_us` (
    `title`,
    `description`,
    `icon`,
    `background_image`,
    `color_theme`,
    `sort_order`,
    `is_active`,
    `created_at`,
    `updated_at`
) VALUES (
    'Pelayanan Terbaik',
    'Kami memberikan pelayanan terbaik untuk semua nasabah dengan standar profesional tinggi.',
    'why-choose-us/icons/service-icon.png',
    'why-choose-us/backgrounds/service-bg.jpg',
    'primary',
    1,
    1,
    NOW(),
    NOW()
);

-- Update settings dengan gambar
UPDATE `why_choose_us_settings` 
SET 
    `section_image` = 'why-choose-us/section/main-image.jpg',
    `badge_icon` = 'why-choose-us/badges/checkmark.png',
    `updated_at` = NOW()
WHERE 
    `id` = 1;


-- ============================================
-- 7. STATISTIK
-- ============================================

-- Statistik items
SELECT 
    COUNT(*) AS total_items,
    SUM(CASE WHEN icon IS NOT NULL THEN 1 ELSE 0 END) AS items_with_icon,
    SUM(CASE WHEN background_image IS NOT NULL THEN 1 ELSE 0 END) AS items_with_background,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_items
FROM 
    `why_choose_us`;

-- Check settings
SELECT 
    section_title,
    CASE 
        WHEN section_image IS NOT NULL THEN 'Ada gambar section'
        ELSE 'Belum ada gambar'
    END AS section_image_status,
    CASE 
        WHEN badge_icon IS NOT NULL THEN 'Ada icon badge'
        ELSE 'Belum ada icon'
    END AS badge_icon_status,
    CASE 
        WHEN is_active = 1 THEN 'Section aktif'
        ELSE 'Section nonaktif'
    END AS status
FROM 
    `why_choose_us_settings`
WHERE 
    id = 1;


-- ============================================
-- 8. BACKUP (SEBELUM PERUBAHAN)
-- ============================================

-- Backup tabel why_choose_us
CREATE TABLE `why_choose_us_backup` AS 
SELECT * FROM `why_choose_us`;

-- Backup tabel why_choose_us_settings (jika sudah ada)
CREATE TABLE `why_choose_us_settings_backup` AS 
SELECT * FROM `why_choose_us_settings`;


-- ============================================
-- 9. RESTORE (JIKA PERLU)
-- ============================================

-- Restore why_choose_us
-- TRUNCATE TABLE `why_choose_us`;
-- INSERT INTO `why_choose_us` SELECT * FROM `why_choose_us_backup`;

-- Restore why_choose_us_settings
-- TRUNCATE TABLE `why_choose_us_settings`;
-- INSERT INTO `why_choose_us_settings` SELECT * FROM `why_choose_us_settings_backup`;


-- ============================================
-- 10. ROLLBACK (HAPUS PERUBAHAN)
-- ============================================

-- HATI-HATI! Ini akan menghapus semua perubahan

-- Hapus kolom background_image
-- ALTER TABLE `why_choose_us` DROP COLUMN `background_image`;

-- Hapus tabel settings
-- DROP TABLE IF EXISTS `why_choose_us_settings`;


-- ============================================
-- 11. CLEANUP (HAPUS BACKUP)
-- ============================================

-- Setelah yakin semua OK, hapus backup
-- DROP TABLE IF EXISTS `why_choose_us_backup`;
-- DROP TABLE IF EXISTS `why_choose_us_settings_backup`;


-- ============================================
-- NOTES & BEST PRACTICES
-- ============================================

/*
1. STRUKTUR FOLDER STORAGE:
   storage/app/public/
   └── why-choose-us/
       ├── icons/              ← Icon untuk items
       ├── backgrounds/        ← Background image untuk items
       ├── section/            ← Section image utama
       └── badges/             ← Badge icons

2. PATH FORMAT:
   - Disimpan relatif dari storage/app/public/
   - Contoh: 'why-choose-us/section/main-image.jpg'
   - URL: http://domain.com/storage/why-choose-us/section/main-image.jpg

3. IMAGE SPECIFICATIONS:
   - Icon: PNG/SVG, max 2MB, 64x64px
   - Background: JPG/PNG/WEBP, max 5MB, 1200x600px
   - Section Image: JPG/PNG/WEBP, max 5MB, 1200x800px
   - Badge Icon: PNG/SVG, max 2MB, 48x48px

4. SINGLETON PATTERN:
   - Tabel why_choose_us_settings hanya punya 1 record (id=1)
   - Tidak perlu insert multiple records
   - Update existing record saja

5. VALIDATION:
   - section_title: required, max 255 chars
   - section_subtitle: optional, text
   - section_image: optional, image, max 5MB
   - badge_text: optional, max 255 chars
   - badge_icon: optional, image, max 2MB
   - is_active: boolean

6. FRONTEND USAGE:
   $settings = \App\Models\WhyChooseUsSetting::getSettings();
   if ($settings->is_active) {
       // Display section
   }
*/


-- ============================================
-- SUMMARY
-- ============================================

/*
PERUBAHAN DATABASE:

1. Tabel: why_choose_us
   - Tambah kolom: background_image (VARCHAR 255, NULL)

2. Tabel: why_choose_us_settings (BARU)
   - id (PK)
   - section_title (VARCHAR 255, NOT NULL)
   - section_subtitle (TEXT, NULL)
   - section_image (VARCHAR 255, NULL)
   - badge_text (VARCHAR 255, NULL)
   - badge_icon (VARCHAR 255, NULL)
   - is_active (TINYINT 1, DEFAULT 1)
   - created_at, updated_at (TIMESTAMP)

STATUS: ✅ SELESAI
*/
