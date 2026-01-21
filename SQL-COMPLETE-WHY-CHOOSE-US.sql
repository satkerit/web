-- ============================================
-- SQL LENGKAP: WHY CHOOSE US FEATURE
-- ============================================
-- Database: Laravel Application
-- Tanggal: 21 Januari 2026
-- Deskripsi: SQL untuk membuat tabel dan data awal Why Choose Us
-- ============================================

-- 1. BUAT TABEL WHY_CHOOSE_US (Items)
-- ============================================
CREATE TABLE IF NOT EXISTS `why_choose_us` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Judul keunggulan',
  `description` text NOT NULL COMMENT 'Deskripsi keunggulan',
  `icon` varchar(255) DEFAULT NULL COMMENT 'Path icon (SVG/PNG)',
  `color_theme` varchar(50) NOT NULL DEFAULT 'primary' COMMENT 'Tema warna: primary, emerald, blue, amber, rose, purple, teal, cyan, indigo',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Urutan tampil (semakin kecil semakin awal)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status aktif (1=aktif, 0=nonaktif)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel untuk menyimpan item keunggulan';

-- 2. BUAT TABEL WHY_CHOOSE_US_SETTINGS (Section Settings)
-- ============================================
CREATE TABLE IF NOT EXISTS `why_choose_us_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_title` varchar(255) NOT NULL DEFAULT 'Mengapa Memilih Kami' COMMENT 'Judul section',
  `section_subtitle` text DEFAULT NULL COMMENT 'Subtitle section',
  `section_image` varchar(255) DEFAULT NULL COMMENT 'Path gambar section utama',
  `badge_text` varchar(255) DEFAULT NULL COMMENT 'Teks badge (contoh: 100% Syariah Compliant)',
  `badge_icon` varchar(255) DEFAULT NULL COMMENT 'Path icon badge',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status aktif section',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel untuk pengaturan section Why Choose Us (singleton)';

-- 3. INSERT DATA DEFAULT SETTINGS (Singleton Record)
-- ============================================
INSERT INTO `why_choose_us_settings` 
  (`id`, `section_title`, `section_subtitle`, `section_image`, `badge_text`, `badge_icon`, `is_active`, `created_at`, `updated_at`) 
VALUES 
  (1, 'Mengapa Memilih Kami', 'Kami memberikan pelayanan terbaik dengan berbagai keunggulan', NULL, '100% Syariah Compliant', NULL, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  `updated_at` = NOW();

-- 4. INSERT DATA CONTOH ITEMS (Optional)
-- ============================================
-- Hapus komentar di bawah jika ingin insert data contoh

-- INSERT INTO `why_choose_us` 
--   (`title`, `description`, `icon`, `color_theme`, `sort_order`, `is_active`, `created_at`, `updated_at`) 
-- VALUES
--   ('Pelayanan Terbaik', 'Kami memberikan pelayanan terbaik kepada setiap nasabah dengan profesional dan ramah', NULL, 'primary', 1, 1, NOW(), NOW()),
--   ('Proses Cepat', 'Proses pengajuan dan persetujuan yang cepat dan mudah tanpa ribet', NULL, 'emerald', 2, 1, NOW(), NOW()),
--   ('Bunga Kompetitif', 'Suku bunga yang kompetitif dan transparan tanpa biaya tersembunyi', NULL, 'blue', 3, 1, NOW(), NOW()),
--   ('Aman & Terpercaya', 'Terdaftar dan diawasi oleh OJK, keamanan dana Anda terjamin', NULL, 'amber', 4, 1, NOW(), NOW());

-- 5. VERIFIKASI DATA
-- ============================================
-- Cek tabel sudah dibuat
SELECT 
    TABLE_NAME, 
    TABLE_ROWS, 
    CREATE_TIME, 
    TABLE_COMMENT
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME IN ('why_choose_us', 'why_choose_us_settings');

-- Cek data settings
SELECT * FROM why_choose_us_settings;

-- Cek data items
SELECT id, title, color_theme, sort_order, is_active FROM why_choose_us ORDER BY sort_order;

-- 6. STRUKTUR STORAGE
-- ============================================
-- Pastikan folder storage sudah dibuat:
-- storage/app/public/why-choose-us/
-- ├── icons/       ← Icon untuk items (64x64px, PNG/SVG)
-- ├── section/     ← Gambar section utama (1200x800px, JPG/PNG/WEBP)
-- └── badges/      ← Icon badge (48x48px, PNG/SVG)

-- 7. ROLLBACK (Jika diperlukan)
-- ============================================
-- HATI-HATI! Perintah di bawah akan menghapus semua data

-- DROP TABLE IF EXISTS `why_choose_us_settings`;
-- DROP TABLE IF EXISTS `why_choose_us`;

-- ============================================
-- SELESAI
-- ============================================
-- Setelah menjalankan SQL ini:
-- 1. Jalankan: php artisan storage:link (jika belum)
-- 2. Buat folder: storage/app/public/why-choose-us/icons
-- 3. Buat folder: storage/app/public/why-choose-us/section
-- 4. Buat folder: storage/app/public/why-choose-us/badges
-- 5. Akses admin panel: /admin/why-choose-us
-- 6. Akses settings: /admin/why-choose-us-settings
-- ============================================
