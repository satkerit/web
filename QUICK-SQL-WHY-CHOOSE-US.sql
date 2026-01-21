-- ============================================
-- QUICK SQL - Why Choose Us System
-- Copy & Paste ke phpMyAdmin atau MySQL Client
-- ============================================

-- 1. TAMBAH KOLOM background_image
ALTER TABLE `why_choose_us` 
ADD COLUMN `background_image` VARCHAR(255) NULL 
AFTER `icon`;

-- 2. CREATE TABLE why_choose_us_settings
CREATE TABLE `why_choose_us_settings` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `section_title` VARCHAR(255) NOT NULL DEFAULT 'Mengapa Memilih Kami',
    `section_subtitle` TEXT NULL,
    `section_image` VARCHAR(255) NULL,
    `badge_text` VARCHAR(255) NULL,
    `badge_icon` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. INSERT DEFAULT SETTINGS
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
);

-- 4. VERIFIKASI
DESCRIBE `why_choose_us`;
DESCRIBE `why_choose_us_settings`;
SELECT * FROM `why_choose_us_settings`;

-- ============================================
-- SELESAI!
-- ============================================
