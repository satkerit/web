-- =====================================================
-- DATABASE FIX FOR PRODUCTION ERROR 500
-- =====================================================
-- Jalankan SQL ini di phpMyAdmin untuk memperbaiki data auction
-- yang menyebabkan error 500 pada halaman home
-- =====================================================

-- 1. Buat tabel auctions jika belum ada (minimal version)
CREATE TABLE IF NOT EXISTS `auctions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) DEFAULT NULL,
    `auction_number` varchar(100) DEFAULT NULL,
    `asset_type` varchar(50) DEFAULT 'rumah',
    `address` text DEFAULT NULL,
    `city` varchar(255) DEFAULT 'Pangkalpinang',
    `province` varchar(255) DEFAULT 'Kepulauan Bangka Belitung',
    `auction_type` varchar(50) DEFAULT 'eksekusi_hak_tanggungan',
    `limit_price` decimal(15,2) DEFAULT 100000000,
    `auction_date` datetime DEFAULT NULL,
    `status` varchar(50) DEFAULT 'published',
    `contact_person` varchar(255) DEFAULT 'Customer Service',
    `contact_phone` varchar(20) DEFAULT '0717-123456',
    `organizer_name` varchar(255) DEFAULT 'BPRS Babel',
    `images` json DEFAULT NULL,
    `published_at` datetime DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tambahkan kolom yang mungkin belum ada
ALTER TABLE `auctions` ADD COLUMN IF NOT EXISTS `city` varchar(255) DEFAULT 'Pangkalpinang';
ALTER TABLE `auctions` ADD COLUMN IF NOT EXISTS `limit_price` decimal(15,2) DEFAULT 100000000;
ALTER TABLE `auctions` ADD COLUMN IF NOT EXISTS `status` varchar(50) DEFAULT 'published';
ALTER TABLE `auctions` ADD COLUMN IF NOT EXISTS `organizer_name` varchar(255) DEFAULT 'BPRS Babel';
ALTER TABLE `auctions` ADD COLUMN IF NOT EXISTS `province` varchar(255) DEFAULT 'Kepulauan Bangka Belitung';

-- 3. Perbaiki data yang bermasalah
-- Fix null city values
UPDATE `auctions` 
SET `city` = 'Pangkalpinang' 
WHERE `city` IS NULL OR `city` = '';

-- Fix null atau zero limit_price values
UPDATE `auctions` 
SET `limit_price` = 100000000 
WHERE `limit_price` IS NULL OR `limit_price` <= 0;

-- Fix invalid status values
UPDATE `auctions` 
SET `status` = 'published' 
WHERE `status` IS NULL 
   OR `status` = '' 
   OR `status` NOT IN ('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed');

-- Set default organizer info jika null
UPDATE `auctions` 
SET 
    `organizer_name` = 'BPRS Babel',
    `province` = 'Kepulauan Bangka Belitung'
WHERE `organizer_name` IS NULL OR `organizer_name` = '';

-- Generate auction_number jika null
UPDATE `auctions` 
SET `auction_number` = CONCAT('LEL/', YEAR(COALESCE(created_at, NOW())), '/', LPAD(id, 3, '0'))
WHERE `auction_number` IS NULL OR `auction_number` = '';

-- Generate slug jika null
UPDATE `auctions` 
SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(title, ' ', '-'), '--', '-'), '---', '-'), '----', '-'))
WHERE `slug` IS NULL OR `slug` = '';

-- Set published_at untuk auction yang published
UPDATE `auctions` 
SET `published_at` = COALESCE(created_at, NOW())
WHERE `status` IN ('published', 'registration_open', 'auction_scheduled', 'sold') 
  AND `published_at` IS NULL;

-- 4. Insert sample data jika tabel kosong
INSERT IGNORE INTO `auctions` (
    `title`, `slug`, `auction_number`, `asset_type`, `address`, `city`, `province`,
    `auction_type`, `limit_price`, `status`, `contact_person`, `contact_phone`,
    `organizer_name`, `published_at`, `created_at`, `updated_at`
) 
SELECT * FROM (
    SELECT 
        'Rumah Tinggal Strategis' as title,
        'rumah-tinggal-strategis' as slug,
        'LEL/2026/001' as auction_number,
        'rumah' as asset_type,
        'Jl. Jenderal Sudirman No. 123, Pangkalpinang' as address,
        'Pangkalpinang' as city,
        'Kepulauan Bangka Belitung' as province,
        'eksekusi_hak_tanggungan' as auction_type,
        750000000.00 as limit_price,
        'published' as status,
        'Ahmad Fauzi' as contact_person,
        '0717-123456' as contact_phone,
        'BPRS Babel' as organizer_name,
        NOW() as published_at,
        NOW() as created_at,
        NOW() as updated_at
    UNION ALL
    SELECT 
        'Tanah Komersial Strategis' as title,
        'tanah-komersial-strategis' as slug,
        'LEL/2026/002' as auction_number,
        'tanah' as asset_type,
        'Jl. Ahmad Yani No. 45, Sungailiat' as address,
        'Sungailiat' as city,
        'Kepulauan Bangka Belitung' as province,
        'eksekusi_hak_tanggungan' as auction_type,
        500000000.00 as limit_price,
        'published' as status,
        'Siti Nurhaliza' as contact_person,
        '0717-234567' as contact_phone,
        'BPRS Babel' as organizer_name,
        NOW() as published_at,
        NOW() as created_at,
        NOW() as updated_at
    UNION ALL
    SELECT 
        'Ruko 2 Lantai Strategis' as title,
        'ruko-2-lantai-strategis' as slug,
        'LEL/2026/003' as auction_number,
        'ruko' as asset_type,
        'Jl. Diponegoro No. 67, Toboali' as address,
        'Toboali' as city,
        'Kepulauan Bangka Belitung' as province,
        'non_eksekusi_sukarela' as auction_type,
        1200000000.00 as limit_price,
        'published' as status,
        'Budi Santoso' as contact_person,
        '0717-345678' as contact_phone,
        'BPRS Babel' as organizer_name,
        NOW() as published_at,
        NOW() as created_at,
        NOW() as updated_at
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM `auctions` LIMIT 1
);

-- 5. Verifikasi hasil perbaikan
SELECT 
    COUNT(*) as total_auctions,
    COUNT(CASE WHEN city IS NOT NULL AND city != '' THEN 1 END) as with_city,
    COUNT(CASE WHEN limit_price > 0 THEN 1 END) as with_price,
    COUNT(CASE WHEN status IN ('published', 'registration_open', 'auction_scheduled') THEN 1 END) as published_count,
    COUNT(CASE WHEN organizer_name IS NOT NULL AND organizer_name != '' THEN 1 END) as with_organizer
FROM `auctions`;

-- 6. Tampilkan sample data
SELECT 
    id, title, city, limit_price, status, auction_date, organizer_name
FROM `auctions` 
ORDER BY created_at DESC 
LIMIT 5;

-- =====================================================
-- SELESAI - DATABASE FIX COMPLETED
-- =====================================================
-- Setelah menjalankan SQL ini:
-- 1. Test halaman home website
-- 2. Jika masih error, jalankan clear_cache.php
-- 3. Jika masih bermasalah, check error log
-- =====================================================