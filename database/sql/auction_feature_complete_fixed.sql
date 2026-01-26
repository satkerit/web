-- =====================================================
-- AUCTION FEATURE COMPLETE SQL - FIXED VERSION
-- =====================================================
-- File ini berisi semua perubahan untuk fitur lelang
-- Termasuk: pembuatan tabel, data sample, perbaikan kolom, menu, dan permissions
-- Dibuat: 26 Januari 2026
-- =====================================================

-- 1. CREATE AUCTIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `auctions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Basic Information
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) DEFAULT NULL,
    `description` longtext DEFAULT NULL,
    `auction_number` varchar(100) NOT NULL UNIQUE,
    `object_number` varchar(100) DEFAULT NULL,
    
    -- Asset Information
    `asset_type` enum('tanah','rumah','ruko','apartemen','gedung','pabrik','kendaraan','mesin','lainnya') NOT NULL,
    `asset_category` varchar(255) DEFAULT NULL,
    `asset_description` longtext DEFAULT NULL,
    
    -- Certificate Information
    `certificate_type` enum('SHM','SHGB','SHP','AJB','PPJB','Girik','BPKB','Lainnya') DEFAULT NULL,
    `certificate_number` varchar(255) DEFAULT NULL,
    `certificate_date` date DEFAULT NULL,
    `certificate_issued_by` varchar(255) DEFAULT NULL,
    
    -- Property Details
    `land_area` decimal(10,2) DEFAULT NULL,
    `building_area` decimal(10,2) DEFAULT NULL,
    `building_condition` varchar(255) DEFAULT NULL,
    `floors` int(11) DEFAULT NULL,
    `bedrooms` int(11) DEFAULT NULL,
    `bathrooms` int(11) DEFAULT NULL,
    `parking_spaces` int(11) DEFAULT NULL,
    `year_built` int(11) DEFAULT NULL,
    
    -- Location Details
    `address` text NOT NULL,
    `village` varchar(255) DEFAULT NULL,
    `district` varchar(255) DEFAULT NULL,
    `city` varchar(255) DEFAULT NULL,
    `province` varchar(255) DEFAULT NULL,
    `postal_code` varchar(10) DEFAULT NULL,
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    
    -- Debtor Information
    `debtor_name` varchar(255) DEFAULT NULL,
    `debtor_id_number` varchar(20) DEFAULT NULL,
    `debtor_address` text DEFAULT NULL,
    
    -- Auction Information
    `auction_type` enum('eksekusi_hak_tanggungan','eksekusi_fidusia','eksekusi_hipotik','non_eksekusi_wajib','non_eksekusi_sukarela') NOT NULL,
    `auction_method` varchar(255) DEFAULT NULL,
    `auction_date` datetime DEFAULT NULL,
    `auction_time` time DEFAULT NULL,
    `auction_location` varchar(255) NOT NULL,
    `auction_address` text DEFAULT NULL,
    
    -- Registration
    `registration_start` datetime DEFAULT NULL,
    `registration_end` datetime DEFAULT NULL,
    `registration_requirements` longtext DEFAULT NULL,
    `registration_procedure` longtext DEFAULT NULL,
    
    -- Pricing (menggunakan nama kolom baru)
    `limit_price` decimal(15,2) NOT NULL,
    `estimated_price` decimal(15,2) DEFAULT NULL,
    `deposit_amount` decimal(15,2) DEFAULT NULL,
    `deposit_percentage` decimal(5,2) DEFAULT NULL,
    `increment_amount` decimal(15,2) DEFAULT NULL,
    
    -- Bank Information
    `bank_name` varchar(255) DEFAULT NULL,
    `bank_branch` varchar(255) DEFAULT NULL,
    `account_number` varchar(50) DEFAULT NULL,
    `account_holder` varchar(255) DEFAULT NULL,
    `swift_code` varchar(20) DEFAULT NULL,
    
    -- Legal Information
    `creditor_name` varchar(255) DEFAULT NULL,
    `creditor_address` text DEFAULT NULL,
    `legal_basis` varchar(255) DEFAULT NULL,
    `court_decision` varchar(255) DEFAULT NULL,
    `court_decision_date` date DEFAULT NULL,
    `debt_amount` decimal(15,2) DEFAULT NULL,
    `encumbrance_details` longtext DEFAULT NULL,
    
    -- Viewing Information
    `viewing_start` datetime DEFAULT NULL,
    `viewing_end` datetime DEFAULT NULL,
    `viewing_schedule` longtext DEFAULT NULL,
    `viewing_contact` varchar(255) DEFAULT NULL,
    `viewing_notes` longtext DEFAULT NULL,
    
    -- Terms & Conditions
    `terms_conditions` longtext DEFAULT NULL,
    `special_conditions` longtext DEFAULT NULL,
    `payment_terms` longtext DEFAULT NULL,
    `payment_deadline_days` int(11) DEFAULT NULL,
    `delivery_terms` longtext DEFAULT NULL,
    
    -- Organizer Information
    `organizer_name` varchar(255) DEFAULT NULL,
    `organizer_type` varchar(255) DEFAULT NULL,
    `organizer_address` text DEFAULT NULL,
    `organizer_phone` varchar(20) DEFAULT NULL,
    `organizer_email` varchar(255) DEFAULT NULL,
    `organizer_website` varchar(255) DEFAULT NULL,
    
    -- Contact Information
    `contact_person` varchar(255) NOT NULL,
    `contact_position` varchar(255) DEFAULT NULL,
    `contact_phone` varchar(20) NOT NULL,
    `contact_email` varchar(255) DEFAULT NULL,
    `contact_whatsapp` varchar(20) DEFAULT NULL,
    `contact_office_hours` varchar(255) DEFAULT NULL,
    
    -- Documents & Media
    `images` json DEFAULT NULL,
    `documents` json DEFAULT NULL,
    `floor_plans` json DEFAULT NULL,
    `certificates` json DEFAULT NULL,
    `virtual_tour_url` varchar(255) DEFAULT NULL,
    `video_url` varchar(255) DEFAULT NULL,
    
    -- Status & Results
    `status` enum('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed') NOT NULL DEFAULT 'draft',
    `status_notes` longtext DEFAULT NULL,
    
    -- Auction Results
    `winning_bid` decimal(15,2) DEFAULT NULL,
    `winner_name` varchar(255) DEFAULT NULL,
    `winner_id_number` varchar(20) DEFAULT NULL,
    `winner_address` text DEFAULT NULL,
    `winner_phone` varchar(20) DEFAULT NULL,
    `sold_at` datetime DEFAULT NULL,
    `auction_notes` longtext DEFAULT NULL,
    `total_bidders` int(11) DEFAULT NULL,
    `total_bids` int(11) DEFAULT NULL,
    
    -- Additional Information
    `facilities` longtext DEFAULT NULL,
    `nearby_facilities` longtext DEFAULT NULL,
    `transportation_access` longtext DEFAULT NULL,
    `investment_potential` longtext DEFAULT NULL,
    `market_analysis` longtext DEFAULT NULL,
    `risk_factors` longtext DEFAULT NULL,
    
    -- SEO & Meta
    `meta_title` varchar(255) DEFAULT NULL,
    `meta_description` varchar(500) DEFAULT NULL,
    `meta_keywords` varchar(255) DEFAULT NULL,
    
    -- Tracking
    `view_count` int(11) NOT NULL DEFAULT 0,
    `interest_count` int(11) NOT NULL DEFAULT 0,
    `download_count` int(11) NOT NULL DEFAULT 0,
    
    -- Publishing
    `published_at` datetime DEFAULT NULL,
    `featured_until` datetime DEFAULT NULL,
    `is_featured` tinyint(1) NOT NULL DEFAULT 0,
    `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
    `sort_order` int(11) NOT NULL DEFAULT 0,
    
    -- Timestamps
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `auctions_auction_number_unique` (`auction_number`),
    UNIQUE KEY `auctions_slug_unique` (`slug`),
    KEY `auctions_asset_type_index` (`asset_type`),
    KEY `auctions_city_index` (`city`),
    KEY `auctions_status_index` (`status`),
    KEY `auctions_auction_date_index` (`auction_date`),
    KEY `auctions_limit_price_index` (`limit_price`),
    KEY `auctions_is_featured_index` (`is_featured`),
    KEY `auctions_published_at_index` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. MIGRATE EXISTING DATA (jika ada kolom lama)
-- =====================================================
-- Update existing data from old column names to new ones
UPDATE `auctions` SET 
    `city` = `location`,
    `limit_price` = `starting_price`
WHERE `location` IS NOT NULL OR `starting_price` IS NOT NULL;

-- 3. ADD ADMIN MENU FOR AUCTIONS
-- =====================================================
INSERT IGNORE INTO `admin_menus` (`id`, `name`, `url`, `icon`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(NULL, 'Kelola Lelang', '/admin/auctions', 'fas fa-gavel', NULL, 8, 1, NOW(), NOW());

-- Get the menu ID for permissions
SET @auction_menu_id = (SELECT id FROM admin_menus WHERE url = '/admin/auctions' LIMIT 1);

-- 4. CREATE ROLES AND PERMISSIONS
-- =====================================================
-- Insert roles if they don't exist
INSERT IGNORE INTO `roles` (`name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
('super_admin', 'Super Administrator', 'Full access to all features', NOW(), NOW()),
('admin', 'Administrator', 'Access to most features', NOW(), NOW()),
('manager', 'Manager', 'Limited administrative access', NOW(), NOW()),
('staff', 'Staff', 'Basic access', NOW(), NOW());

-- Insert permissions if they don't exist
INSERT IGNORE INTO `permissions` (`name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
('auctions.view', 'View Auctions', 'Can view auction listings', NOW(), NOW()),
('auctions.create', 'Create Auctions', 'Can create new auctions', NOW(), NOW()),
('auctions.edit', 'Edit Auctions', 'Can edit existing auctions', NOW(), NOW()),
('auctions.delete', 'Delete Auctions', 'Can delete auctions', NOW(), NOW()),
('auctions.publish', 'Publish Auctions', 'Can publish/unpublish auctions', NOW(), NOW()),
('auctions.feature', 'Feature Auctions', 'Can feature/unfeature auctions', NOW(), NOW());

-- 5. CREATE ADMIN MENU PERMISSIONS
-- =====================================================
-- Get role IDs
SET @super_admin_role_id = (SELECT id FROM roles WHERE name = 'super_admin' LIMIT 1);
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'admin' LIMIT 1);
SET @manager_role_id = (SELECT id FROM roles WHERE name = 'manager' LIMIT 1);

-- Create menu permissions for different roles
INSERT IGNORE INTO `admin_menu_permissions` (`menu_id`, `role_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
(@auction_menu_id, @super_admin_role_id, 1, 1, 1, 1, NOW(), NOW()),
(@auction_menu_id, @admin_role_id, 1, 1, 1, 1, NOW(), NOW()),
(@auction_menu_id, @manager_role_id, 1, 1, 1, 0, NOW(), NOW());

-- 6. SAMPLE DATA
-- =====================================================
INSERT IGNORE INTO `auctions` (
    `title`, `slug`, `description`, `auction_number`, `object_number`,
    `asset_type`, `asset_category`, `certificate_type`, `certificate_number`,
    `address`, `city`, `province`, `auction_type`, `auction_date`,
    `auction_location`, `limit_price`, `estimated_price`, `deposit_percentage`,
    `status`, `contact_person`, `contact_phone`, `organizer_name`,
    `published_at`, `is_featured`, `view_count`, `created_at`, `updated_at`
) VALUES
(
    'Rumah Tinggal Strategis di Pangkalpinang',
    'rumah-tinggal-strategis-di-pangkalpinang',
    'Rumah tinggal dengan lokasi strategis di pusat kota Pangkalpinang. Kondisi terawat dengan fasilitas lengkap.',
    'LEL/2026/001',
    'OBJ-2026-001',
    'rumah',
    'Rumah Type 45',
    'SHM',
    '1234/2020/SHM',
    'Jl. Jenderal Sudirman No. 123, Pangkalpinang',
    'Pangkalpinang',
    'Kepulauan Bangka Belitung',
    'eksekusi_hak_tanggungan',
    '2026-03-15 10:00:00',
    'Kantor BPRS Babel',
    750000000.00,
    850000000.00,
    20.00,
    'published',
    'Ahmad Fauzi',
    '0717-123456',
    'BPRS Babel',
    NOW(),
    1,
    125,
    NOW(),
    NOW()
),
(
    'Tanah Komersial di Sungailiat',
    'tanah-komersial-di-sungailiat',
    'Tanah komersial dengan lokasi strategis cocok untuk investasi atau pembangunan ruko.',
    'LEL/2026/002',
    'OBJ-2026-002',
    'tanah',
    'Tanah Komersial',
    'SHM',
    '5678/2019/SHM',
    'Jl. Ahmad Yani No. 45, Sungailiat',
    'Sungailiat',
    'Kepulauan Bangka Belitung',
    'eksekusi_hak_tanggungan',
    '2026-03-20 14:00:00',
    'Kantor BPRS Babel Cabang Sungailiat',
    500000000.00,
    600000000.00,
    15.00,
    'registration_open',
    'Siti Nurhaliza',
    '0717-234567',
    'BPRS Babel',
    NOW(),
    0,
    89,
    NOW(),
    NOW()
),
(
    'Ruko 2 Lantai di Toboali',
    'ruko-2-lantai-di-toboali',
    'Ruko 2 lantai dengan lokasi strategis di jalan utama Toboali. Cocok untuk berbagai jenis usaha.',
    'LEL/2026/003',
    'OBJ-2026-003',
    'ruko',
    'Ruko 2 Lantai',
    'SHGB',
    '9012/2021/SHGB',
    'Jl. Diponegoro No. 67, Toboali',
    'Toboali',
    'Kepulauan Bangka Belitung',
    'non_eksekusi_sukarela',
    '2026-04-10 09:00:00',
    'Kantor BPRS Babel Cabang Toboali',
    1200000000.00,
    1400000000.00,
    25.00,
    'auction_scheduled',
    'Budi Santoso',
    '0717-345678',
    'BPRS Babel',
    NOW(),
    1,
    156,
    NOW(),
    NOW()
),
(
    'Kendaraan Bermotor - Toyota Avanza',
    'kendaraan-bermotor-toyota-avanza',
    'Toyota Avanza tahun 2020, kondisi terawat, surat-surat lengkap.',
    'LEL/2026/004',
    'OBJ-2026-004',
    'kendaraan',
    'Mobil',
    'BPKB',
    'B1234XYZ',
    'Jl. Veteran No. 12, Pangkalpinang',
    'Pangkalpinang',
    'Kepulauan Bangka Belitung',
    'eksekusi_fidusia',
    '2026-02-28 11:00:00',
    'Kantor BPRS Babel',
    180000000.00,
    200000000.00,
    10.00,
    'sold',
    'Rina Marlina',
    '0717-456789',
    'BPRS Babel',
    NOW(),
    0,
    67,
    NOW(),
    NOW()
),
(
    'Gedung Perkantoran di Muntok',
    'gedung-perkantoran-di-muntok',
    'Gedung perkantoran 3 lantai dengan fasilitas lengkap di pusat kota Muntok.',
    'LEL/2026/005',
    'OBJ-2026-005',
    'gedung',
    'Gedung Komersial',
    'SHM',
    '3456/2018/SHM',
    'Jl. Gajah Mada No. 89, Muntok',
    'Muntok',
    'Kepulauan Bangka Belitung',
    'eksekusi_hak_tanggungan',
    '2026-04-25 13:00:00',
    'Kantor BPRS Babel Cabang Muntok',
    2500000000.00,
    2800000000.00,
    30.00,
    'published',
    'Dedi Kurniawan',
    '0717-567890',
    'BPRS Babel',
    NOW(),
    1,
    203,
    NOW(),
    NOW()
);

-- 7. UPDATE EXISTING AUCTION DATA (jika diperlukan)
-- =====================================================
-- Set default values for new columns
UPDATE `auctions` SET 
    `organizer_name` = 'BPRS Babel',
    `organizer_type` = 'Bank Pembiayaan Rakyat Syariah',
    `organizer_address` = 'Jl. Jenderal Sudirman No. 1, Pangkalpinang',
    `organizer_phone` = '0717-123456',
    `organizer_email` = 'info@bprsbabel.co.id',
    `province` = 'Kepulauan Bangka Belitung'
WHERE `organizer_name` IS NULL;

-- Update status values to new enum values
UPDATE `auctions` SET `status` = 'published' WHERE `status` = 'active';
UPDATE `auctions` SET `status` = 'draft' WHERE `status` = 'inactive';

-- 8. CREATE INDEXES FOR PERFORMANCE
-- =====================================================
-- Additional indexes for better query performance
CREATE INDEX IF NOT EXISTS `idx_auctions_featured_published` ON `auctions` (`is_featured`, `published_at`);
CREATE INDEX IF NOT EXISTS `idx_auctions_status_date` ON `auctions` (`status`, `auction_date`);
CREATE INDEX IF NOT EXISTS `idx_auctions_city_status` ON `auctions` (`city`, `status`);
CREATE INDEX IF NOT EXISTS `idx_auctions_asset_type_status` ON `auctions` (`asset_type`, `status`);

-- 9. CLEAN UP OLD COLUMNS (jika ada)
-- =====================================================
-- Uncomment these lines if you want to remove old columns after migration
-- ALTER TABLE `auctions` DROP COLUMN IF EXISTS `location`;
-- ALTER TABLE `auctions` DROP COLUMN IF EXISTS `starting_price`;

-- 10. FINAL VERIFICATION
-- =====================================================
-- Verify the table structure
DESCRIBE `auctions`;

-- Count total auctions
SELECT COUNT(*) as total_auctions FROM `auctions`;

-- Show sample data
SELECT 
    `auction_number`,
    `title`,
    `asset_type`,
    `city`,
    `limit_price`,
    `status`,
    `auction_date`
FROM `auctions` 
ORDER BY `created_at` DESC 
LIMIT 5;

-- =====================================================
-- END OF AUCTION FEATURE COMPLETE SQL
-- =====================================================

-- NOTES:
-- 1. File ini mencakup semua perubahan untuk fitur lelang
-- 2. Menggunakan nama kolom baru: city (bukan location), limit_price (bukan starting_price)
-- 3. Termasuk data sample yang realistis untuk testing
-- 4. Menambahkan menu admin dan permissions
-- 5. Menggunakan enum values yang sudah diperbaiki
-- 6. Semua accessor methods di model sudah handle null values
-- 7. Semua view files sudah diperbaiki untuk handle null dates
-- 8. Ready untuk production use

-- CARA PENGGUNAAN:
-- 1. Backup database terlebih dahulu
-- 2. Jalankan file SQL ini di database
-- 3. Verify hasil dengan query di bagian akhir
-- 4. Test fitur lelang di aplikasi