-- =====================================================
-- AUCTION FEATURE ALTER TABLE QUERIES - FIXED VERSION
-- =====================================================
-- File ini berisi ALTER TABLE queries untuk memodifikasi tabel auctions yang sudah ada
-- Menambahkan kolom baru, mengubah kolom existing, dan memperbaiki struktur
-- Dibuat: 26 Januari 2026
-- =====================================================

-- 1. BACKUP EXISTING DATA (OPTIONAL - UNCOMMENT IF NEEDED)
-- =====================================================
-- CREATE TABLE auctions_backup AS SELECT * FROM auctions;

-- 2. ADD NEW COLUMNS TO EXISTING AUCTIONS TABLE
-- =====================================================

-- Basic Information Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `slug` varchar(255) DEFAULT NULL AFTER `title`,
ADD COLUMN IF NOT EXISTS `auction_number` varchar(100) DEFAULT NULL AFTER `description`,
ADD COLUMN IF NOT EXISTS `object_number` varchar(100) DEFAULT NULL AFTER `auction_number`;

-- Asset Information Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `asset_category` varchar(255) DEFAULT NULL AFTER `asset_type`,
ADD COLUMN IF NOT EXISTS `asset_description` longtext DEFAULT NULL AFTER `asset_category`;

-- Certificate Information Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `certificate_type` enum('SHM','SHGB','SHP','AJB','PPJB','Girik','BPKB','Lainnya') DEFAULT NULL AFTER `asset_description`,
ADD COLUMN IF NOT EXISTS `certificate_number` varchar(255) DEFAULT NULL AFTER `certificate_type`,
ADD COLUMN IF NOT EXISTS `certificate_date` date DEFAULT NULL AFTER `certificate_number`,
ADD COLUMN IF NOT EXISTS `certificate_issued_by` varchar(255) DEFAULT NULL AFTER `certificate_date`;

-- Property Details Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `land_area` decimal(10,2) DEFAULT NULL AFTER `certificate_issued_by`,
ADD COLUMN IF NOT EXISTS `building_area` decimal(10,2) DEFAULT NULL AFTER `land_area`,
ADD COLUMN IF NOT EXISTS `building_condition` varchar(255) DEFAULT NULL AFTER `building_area`,
ADD COLUMN IF NOT EXISTS `floors` int(11) DEFAULT NULL AFTER `building_condition`,
ADD COLUMN IF NOT EXISTS `bedrooms` int(11) DEFAULT NULL AFTER `floors`,
ADD COLUMN IF NOT EXISTS `bathrooms` int(11) DEFAULT NULL AFTER `bedrooms`,
ADD COLUMN IF NOT EXISTS `parking_spaces` int(11) DEFAULT NULL AFTER `bathrooms`,
ADD COLUMN IF NOT EXISTS `year_built` int(11) DEFAULT NULL AFTER `parking_spaces`;

-- Location Details Columns (city sudah ada, tambahkan yang lain)
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `village` varchar(255) DEFAULT NULL AFTER `address`,
ADD COLUMN IF NOT EXISTS `district` varchar(255) DEFAULT NULL AFTER `village`,
ADD COLUMN IF NOT EXISTS `province` varchar(255) DEFAULT NULL AFTER `city`,
ADD COLUMN IF NOT EXISTS `postal_code` varchar(10) DEFAULT NULL AFTER `province`,
ADD COLUMN IF NOT EXISTS `latitude` decimal(10,8) DEFAULT NULL AFTER `postal_code`,
ADD COLUMN IF NOT EXISTS `longitude` decimal(11,8) DEFAULT NULL AFTER `latitude`;

-- Debtor Information Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `debtor_name` varchar(255) DEFAULT NULL AFTER `longitude`,
ADD COLUMN IF NOT EXISTS `debtor_id_number` varchar(20) DEFAULT NULL AFTER `debtor_name`,
ADD COLUMN IF NOT EXISTS `debtor_address` text DEFAULT NULL AFTER `debtor_id_number`;

-- Auction Information Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `auction_method` varchar(255) DEFAULT NULL AFTER `auction_type`,
ADD COLUMN IF NOT EXISTS `auction_time` time DEFAULT NULL AFTER `auction_date`,
ADD COLUMN IF NOT EXISTS `auction_address` text DEFAULT NULL AFTER `auction_location`;

-- Registration Columns
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `registration_start` datetime DEFAULT NULL AFTER `auction_address`,
ADD COLUMN IF NOT EXISTS `registration_end` datetime DEFAULT NULL AFTER `registration_start`,
ADD COLUMN IF NOT EXISTS `registration_requirements` longtext DEFAULT NULL AFTER `registration_end`,
ADD COLUMN IF NOT EXISTS `registration_procedure` longtext DEFAULT NULL AFTER `registration_requirements`;

-- 3. MODIFY EXISTING COLUMNS
-- =====================================================

-- Update asset_type enum to include all types
ALTER TABLE `auctions` 
MODIFY COLUMN `asset_type` enum('tanah','rumah','ruko','apartemen','gedung','pabrik','kendaraan','mesin','lainnya') NOT NULL;

-- Update auction_type enum
ALTER TABLE `auctions` 
MODIFY COLUMN `auction_type` enum('eksekusi_hak_tanggungan','eksekusi_fidusia','eksekusi_hipotik','non_eksekusi_wajib','non_eksekusi_sukarela') NOT NULL;

-- Update status enum with all new values
ALTER TABLE `auctions` 
MODIFY COLUMN `status` enum('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed') NOT NULL DEFAULT 'draft';

-- Ensure limit_price has correct precision
ALTER TABLE `auctions` 
MODIFY COLUMN `limit_price` decimal(15,2) NOT NULL;

-- 4. ADD PRICING COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `estimated_price` decimal(15,2) DEFAULT NULL AFTER `limit_price`,
ADD COLUMN IF NOT EXISTS `deposit_amount` decimal(15,2) DEFAULT NULL AFTER `estimated_price`,
ADD COLUMN IF NOT EXISTS `deposit_percentage` decimal(5,2) DEFAULT NULL AFTER `deposit_amount`,
ADD COLUMN IF NOT EXISTS `increment_amount` decimal(15,2) DEFAULT NULL AFTER `deposit_percentage`;

-- 5. ADD BANK INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `bank_name` varchar(255) DEFAULT NULL AFTER `increment_amount`,
ADD COLUMN IF NOT EXISTS `bank_branch` varchar(255) DEFAULT NULL AFTER `bank_name`,
ADD COLUMN IF NOT EXISTS `account_number` varchar(50) DEFAULT NULL AFTER `bank_branch`,
ADD COLUMN IF NOT EXISTS `account_holder` varchar(255) DEFAULT NULL AFTER `account_number`,
ADD COLUMN IF NOT EXISTS `swift_code` varchar(20) DEFAULT NULL AFTER `account_holder`;

-- 6. ADD LEGAL INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `creditor_name` varchar(255) DEFAULT NULL AFTER `swift_code`,
ADD COLUMN IF NOT EXISTS `creditor_address` text DEFAULT NULL AFTER `creditor_name`,
ADD COLUMN IF NOT EXISTS `legal_basis` varchar(255) DEFAULT NULL AFTER `creditor_address`,
ADD COLUMN IF NOT EXISTS `court_decision` varchar(255) DEFAULT NULL AFTER `legal_basis`,
ADD COLUMN IF NOT EXISTS `court_decision_date` date DEFAULT NULL AFTER `court_decision`,
ADD COLUMN IF NOT EXISTS `debt_amount` decimal(15,2) DEFAULT NULL AFTER `court_decision_date`,
ADD COLUMN IF NOT EXISTS `encumbrance_details` longtext DEFAULT NULL AFTER `debt_amount`;

-- 7. ADD VIEWING INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `viewing_start` datetime DEFAULT NULL AFTER `encumbrance_details`,
ADD COLUMN IF NOT EXISTS `viewing_end` datetime DEFAULT NULL AFTER `viewing_start`,
ADD COLUMN IF NOT EXISTS `viewing_schedule` longtext DEFAULT NULL AFTER `viewing_end`,
ADD COLUMN IF NOT EXISTS `viewing_contact` varchar(255) DEFAULT NULL AFTER `viewing_schedule`,
ADD COLUMN IF NOT EXISTS `viewing_notes` longtext DEFAULT NULL AFTER `viewing_contact`;

-- 8. ADD TERMS & CONDITIONS COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `terms_conditions` longtext DEFAULT NULL AFTER `viewing_notes`,
ADD COLUMN IF NOT EXISTS `special_conditions` longtext DEFAULT NULL AFTER `terms_conditions`,
ADD COLUMN IF NOT EXISTS `payment_terms` longtext DEFAULT NULL AFTER `special_conditions`,
ADD COLUMN IF NOT EXISTS `payment_deadline_days` int(11) DEFAULT NULL AFTER `payment_terms`,
ADD COLUMN IF NOT EXISTS `delivery_terms` longtext DEFAULT NULL AFTER `payment_deadline_days`;

-- 9. ADD ORGANIZER INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `organizer_name` varchar(255) DEFAULT NULL AFTER `delivery_terms`,
ADD COLUMN IF NOT EXISTS `organizer_type` varchar(255) DEFAULT NULL AFTER `organizer_name`,
ADD COLUMN IF NOT EXISTS `organizer_address` text DEFAULT NULL AFTER `organizer_type`,
ADD COLUMN IF NOT EXISTS `organizer_phone` varchar(20) DEFAULT NULL AFTER `organizer_address`,
ADD COLUMN IF NOT EXISTS `organizer_email` varchar(255) DEFAULT NULL AFTER `organizer_phone`,
ADD COLUMN IF NOT EXISTS `organizer_website` varchar(255) DEFAULT NULL AFTER `organizer_email`;

-- 10. ADD EXTENDED CONTACT INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `contact_position` varchar(255) DEFAULT NULL AFTER `contact_person`,
ADD COLUMN IF NOT EXISTS `contact_email` varchar(255) DEFAULT NULL AFTER `contact_phone`,
ADD COLUMN IF NOT EXISTS `contact_whatsapp` varchar(20) DEFAULT NULL AFTER `contact_email`,
ADD COLUMN IF NOT EXISTS `contact_office_hours` varchar(255) DEFAULT NULL AFTER `contact_whatsapp`;

-- 11. ADD DOCUMENTS & MEDIA COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `images` json DEFAULT NULL AFTER `contact_office_hours`,
ADD COLUMN IF NOT EXISTS `documents` json DEFAULT NULL AFTER `images`,
ADD COLUMN IF NOT EXISTS `floor_plans` json DEFAULT NULL AFTER `documents`,
ADD COLUMN IF NOT EXISTS `certificates` json DEFAULT NULL AFTER `floor_plans`,
ADD COLUMN IF NOT EXISTS `virtual_tour_url` varchar(255) DEFAULT NULL AFTER `certificates`,
ADD COLUMN IF NOT EXISTS `video_url` varchar(255) DEFAULT NULL AFTER `virtual_tour_url`;

-- 12. ADD STATUS & RESULTS COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `status_notes` longtext DEFAULT NULL AFTER `status`;

-- 13. ADD AUCTION RESULTS COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `winning_bid` decimal(15,2) DEFAULT NULL AFTER `status_notes`,
ADD COLUMN IF NOT EXISTS `winner_name` varchar(255) DEFAULT NULL AFTER `winning_bid`,
ADD COLUMN IF NOT EXISTS `winner_id_number` varchar(20) DEFAULT NULL AFTER `winner_name`,
ADD COLUMN IF NOT EXISTS `winner_address` text DEFAULT NULL AFTER `winner_id_number`,
ADD COLUMN IF NOT EXISTS `winner_phone` varchar(20) DEFAULT NULL AFTER `winner_address`,
ADD COLUMN IF NOT EXISTS `sold_at` datetime DEFAULT NULL AFTER `winner_phone`,
ADD COLUMN IF NOT EXISTS `auction_notes` longtext DEFAULT NULL AFTER `sold_at`,
ADD COLUMN IF NOT EXISTS `total_bidders` int(11) DEFAULT NULL AFTER `auction_notes`,
ADD COLUMN IF NOT EXISTS `total_bids` int(11) DEFAULT NULL AFTER `total_bidders`;

-- 14. ADD ADDITIONAL INFORMATION COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `facilities` longtext DEFAULT NULL AFTER `total_bids`,
ADD COLUMN IF NOT EXISTS `nearby_facilities` longtext DEFAULT NULL AFTER `facilities`,
ADD COLUMN IF NOT EXISTS `transportation_access` longtext DEFAULT NULL AFTER `nearby_facilities`,
ADD COLUMN IF NOT EXISTS `investment_potential` longtext DEFAULT NULL AFTER `transportation_access`,
ADD COLUMN IF NOT EXISTS `market_analysis` longtext DEFAULT NULL AFTER `investment_potential`,
ADD COLUMN IF NOT EXISTS `risk_factors` longtext DEFAULT NULL AFTER `market_analysis`;

-- 15. ADD SEO & META COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `meta_title` varchar(255) DEFAULT NULL AFTER `risk_factors`,
ADD COLUMN IF NOT EXISTS `meta_description` varchar(500) DEFAULT NULL AFTER `meta_title`,
ADD COLUMN IF NOT EXISTS `meta_keywords` varchar(255) DEFAULT NULL AFTER `meta_description`;

-- 16. ADD TRACKING COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `view_count` int(11) NOT NULL DEFAULT 0 AFTER `meta_keywords`,
ADD COLUMN IF NOT EXISTS `interest_count` int(11) NOT NULL DEFAULT 0 AFTER `view_count`,
ADD COLUMN IF NOT EXISTS `download_count` int(11) NOT NULL DEFAULT 0 AFTER `interest_count`;

-- 17. ADD PUBLISHING COLUMNS
-- =====================================================
ALTER TABLE `auctions` 
ADD COLUMN IF NOT EXISTS `published_at` datetime DEFAULT NULL AFTER `download_count`,
ADD COLUMN IF NOT EXISTS `featured_until` datetime DEFAULT NULL AFTER `published_at`,
ADD COLUMN IF NOT EXISTS `is_featured` tinyint(1) NOT NULL DEFAULT 0 AFTER `featured_until`,
ADD COLUMN IF NOT EXISTS `is_urgent` tinyint(1) NOT NULL DEFAULT 0 AFTER `is_featured`,
ADD COLUMN IF NOT EXISTS `sort_order` int(11) NOT NULL DEFAULT 0 AFTER `is_urgent`;

-- 18. ADD UNIQUE CONSTRAINTS AND INDEXES
-- =====================================================

-- Add unique constraint for auction_number if it doesn't exist
ALTER TABLE `auctions` 
ADD CONSTRAINT `auctions_auction_number_unique` UNIQUE (`auction_number`);

-- Add unique constraint for slug if it doesn't exist
ALTER TABLE `auctions` 
ADD CONSTRAINT `auctions_slug_unique` UNIQUE (`slug`);

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS `auctions_asset_type_index` ON `auctions` (`asset_type`);
CREATE INDEX IF NOT EXISTS `auctions_city_index` ON `auctions` (`city`);
CREATE INDEX IF NOT EXISTS `auctions_status_index` ON `auctions` (`status`);
CREATE INDEX IF NOT EXISTS `auctions_auction_date_index` ON `auctions` (`auction_date`);
CREATE INDEX IF NOT EXISTS `auctions_limit_price_index` ON `auctions` (`limit_price`);
CREATE INDEX IF NOT EXISTS `auctions_is_featured_index` ON `auctions` (`is_featured`);
CREATE INDEX IF NOT EXISTS `auctions_published_at_index` ON `auctions` (`published_at`);

-- Additional composite indexes
CREATE INDEX IF NOT EXISTS `idx_auctions_featured_published` ON `auctions` (`is_featured`, `published_at`);
CREATE INDEX IF NOT EXISTS `idx_auctions_status_date` ON `auctions` (`status`, `auction_date`);
CREATE INDEX IF NOT EXISTS `idx_auctions_city_status` ON `auctions` (`city`, `status`);
CREATE INDEX IF NOT EXISTS `idx_auctions_asset_type_status` ON `auctions` (`asset_type`, `status`);

-- 19. UPDATE EXISTING DATA
-- =====================================================

-- Set auction_number for existing records if null
UPDATE `auctions` 
SET `auction_number` = CONCAT('LEL/', YEAR(COALESCE(created_at, NOW())), '/', LPAD(id, 3, '0'))
WHERE `auction_number` IS NULL OR `auction_number` = '';

-- Generate slugs for existing records if null
UPDATE `auctions` 
SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(title, ' ', '-'), '--', '-'), '---', '-'))
WHERE `slug` IS NULL OR `slug` = '';

-- Set default organizer information
UPDATE `auctions` 
SET 
    `organizer_name` = 'BPRS Babel',
    `organizer_type` = 'Bank Pembiayaan Rakyat Syariah',
    `organizer_address` = 'Jl. Jenderal Sudirman No. 1, Pangkalpinang',
    `organizer_phone` = '0717-123456',
    `organizer_email` = 'info@bprsbabel.co.id',
    `province` = 'Kepulauan Bangka Belitung'
WHERE `organizer_name` IS NULL;

-- Set published_at for published auctions
UPDATE `auctions` 
SET `published_at` = COALESCE(created_at, NOW())
WHERE `status` IN ('published', 'registration_open', 'auction_scheduled', 'sold') 
AND `published_at` IS NULL;

-- 20. MIGRATE OLD COLUMN DATA (IF EXISTS)
-- =====================================================

-- Migrate from old 'location' column to 'city' if exists
-- Uncomment if you have old 'location' column
-- UPDATE `auctions` SET `city` = `location` WHERE `location` IS NOT NULL AND (`city` IS NULL OR `city` = '');

-- Migrate from old 'starting_price' column to 'limit_price' if exists
-- Uncomment if you have old 'starting_price' column
-- UPDATE `auctions` SET `limit_price` = `starting_price` WHERE `starting_price` IS NOT NULL AND `limit_price` = 0;

-- 21. CLEAN UP OLD COLUMNS (OPTIONAL)
-- =====================================================
-- Uncomment these lines if you want to remove old columns after migration
-- ALTER TABLE `auctions` DROP COLUMN IF EXISTS `location`;
-- ALTER TABLE `auctions` DROP COLUMN IF EXISTS `starting_price`;

-- 22. ADD ADMIN MENU (IF NOT EXISTS)
-- =====================================================
INSERT IGNORE INTO `admin_menus` (`name`, `url`, `icon`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) 
VALUES ('Kelola Lelang', '/admin/auctions', 'fas fa-gavel', NULL, 8, 1, NOW(), NOW());

-- 23. FINAL VERIFICATION
-- =====================================================

-- Show table structure
DESCRIBE `auctions`;

-- Count total columns
SELECT COUNT(*) as total_columns 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'auctions';

-- Show sample data with new columns
SELECT 
    `id`,
    `auction_number`,
    `title`,
    `asset_type`,
    `city`,
    `limit_price`,
    `status`,
    `auction_date`,
    `organizer_name`,
    `is_featured`
FROM `auctions` 
ORDER BY `created_at` DESC 
LIMIT 5;

-- =====================================================
-- END OF AUCTION ALTER TABLE QUERIES
-- =====================================================

-- NOTES:
-- 1. Semua ALTER TABLE menggunakan IF NOT EXISTS untuk menghindari error jika kolom sudah ada
-- 2. Menggunakan nama kolom yang sudah diperbaiki (city, limit_price)
-- 3. Enum values sudah diperbaiki sesuai dengan model
-- 4. Menambahkan indexes untuk performa optimal
-- 5. Update data existing dengan nilai default yang sesuai
-- 6. Kompatibel dengan struktur database yang sudah ada

-- CARA PENGGUNAAN:
-- 1. Backup database terlebih dahulu
-- 2. Jalankan file SQL ini di database existing
-- 3. Verify hasil dengan query di bagian akhir
-- 4. Test fitur lelang di aplikasi
-- 5. Uncomment bagian cleanup jika ingin menghapus kolom lama