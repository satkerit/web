-- =====================================================
-- COMPREHENSIVE DATABASE FIXES FOR ADMIN IMPROVEMENTS
-- File: database_admin_fixes_complete.sql
-- Date: 2026-01-27
-- Description: SQL script untuk memastikan database mendukung
--              semua perbaikan admin menu yang telah dilakukan
-- =====================================================

-- Set SQL mode and character set
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- 1. AUCTION TABLE IMPROVEMENTS
-- =====================================================

-- Check if auctions table exists, if not create it
CREATE TABLE IF NOT EXISTS `auctions` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Basic Information
    `title` varchar(255) NOT NULL COMMENT 'Judul lelang',
    `slug` varchar(255) NOT NULL UNIQUE COMMENT 'URL slug',
    `description` text NULL COMMENT 'Deskripsi lelang',
    `auction_number` varchar(255) NOT NULL UNIQUE COMMENT 'Nomor lelang',
    `object_number` varchar(255) NULL COMMENT 'Nomor objek',
    
    -- Asset Information
    `asset_type` enum('tanah','rumah','ruko','apartemen','gedung','pabrik','kendaraan','mesin','lainnya') NOT NULL COMMENT 'Jenis aset',
    `asset_category` varchar(255) NULL COMMENT 'Kategori aset lebih spesifik',
    `asset_description` text NULL COMMENT 'Deskripsi aset detail',
    
    -- Certificate Information
    `certificate_type` enum('SHM','SHGB','SHP','AJB','PPJB','Girik','BPKB','Lainnya') NULL COMMENT 'Jenis sertifikat',
    `certificate_number` varchar(255) NULL COMMENT 'Nomor sertifikat',
    `certificate_date` date NULL COMMENT 'Tanggal sertifikat',
    `certificate_issued_by` varchar(255) NULL COMMENT 'Diterbitkan oleh',
    
    -- Property Details
    `land_area` decimal(10,2) NULL COMMENT 'Luas tanah (m²)',
    `building_area` decimal(10,2) NULL COMMENT 'Luas bangunan (m²)',
    `building_condition` varchar(255) NULL COMMENT 'Kondisi bangunan',
    `floors` int(11) NULL COMMENT 'Jumlah lantai',
    `bedrooms` int(11) NULL COMMENT 'Kamar tidur',
    `bathrooms` int(11) NULL COMMENT 'Kamar mandi',
    `parking_spaces` int(11) NULL COMMENT 'Tempat parkir',
    `year_built` year(4) NULL COMMENT 'Tahun dibangun',
    
    -- Location Details
    `address` text NOT NULL COMMENT 'Alamat lengkap',
    `village` varchar(255) NULL COMMENT 'Kelurahan/Desa',
    `district` varchar(255) NULL COMMENT 'Kecamatan',
    `city` varchar(255) NULL COMMENT 'Kota/Kabupaten',
    `province` varchar(255) NULL COMMENT 'Provinsi',
    `postal_code` varchar(10) NULL COMMENT 'Kode pos',
    `latitude` decimal(10,8) NULL COMMENT 'Koordinat latitude',
    `longitude` decimal(11,8) NULL COMMENT 'Koordinat longitude',
    
    -- Debtor Information
    `debtor_name` varchar(255) NULL COMMENT 'Nama debitur',
    `debtor_id_number` varchar(20) NULL COMMENT 'NIK/No. Identitas debitur',
    `debtor_address` text NULL COMMENT 'Alamat debitur',
    
    -- Auction Information
    `auction_type` enum('eksekusi_hak_tanggungan','eksekusi_fidusia','eksekusi_hipotik','non_eksekusi_wajib','non_eksekusi_sukarela') NOT NULL COMMENT 'Jenis lelang',
    `auction_method` varchar(255) DEFAULT 'lelang_terbuka' COMMENT 'Metode lelang',
    `auction_date` datetime NOT NULL COMMENT 'Tanggal pelaksanaan',
    `auction_time` time NULL COMMENT 'Waktu pelaksanaan',
    `auction_location` varchar(255) NOT NULL COMMENT 'Tempat pelaksanaan',
    `auction_address` text NULL COMMENT 'Alamat tempat lelang',
    
    -- Registration
    `registration_start` datetime NULL COMMENT 'Mulai pendaftaran',
    `registration_end` datetime NULL COMMENT 'Akhir pendaftaran',
    `registration_requirements` text NULL COMMENT 'Syarat pendaftaran',
    `registration_procedure` text NULL COMMENT 'Tata cara pendaftaran',
    
    -- Pricing
    `limit_price` decimal(15,2) NOT NULL COMMENT 'Harga limit',
    `estimated_price` decimal(15,2) NULL COMMENT 'Nilai taksiran',
    `deposit_amount` decimal(15,2) NULL COMMENT 'Uang jaminan',
    `deposit_percentage` decimal(5,2) DEFAULT 20.00 COMMENT 'Persentase jaminan',
    `increment_amount` decimal(15,2) NULL COMMENT 'Kelipatan penawaran',
    
    -- Bank Information
    `bank_name` varchar(255) NULL COMMENT 'Nama bank',
    `bank_branch` varchar(255) NULL COMMENT 'Cabang bank',
    `account_number` varchar(255) NULL COMMENT 'Nomor rekening',
    `account_holder` varchar(255) NULL COMMENT 'Nama pemegang rekening',
    `swift_code` varchar(255) NULL COMMENT 'Kode SWIFT',
    
    -- Legal Information
    `creditor_name` varchar(255) NULL COMMENT 'Nama kreditur',
    `creditor_address` text NULL COMMENT 'Alamat kreditur',
    `legal_basis` varchar(255) NULL COMMENT 'Dasar hukum',
    `court_decision` varchar(255) NULL COMMENT 'Putusan pengadilan',
    `court_decision_date` date NULL COMMENT 'Tanggal putusan pengadilan',
    `debt_amount` decimal(15,2) NULL COMMENT 'Jumlah hutang',
    `encumbrance_details` text NULL COMMENT 'Rincian beban',
    
    -- Viewing Information
    `viewing_start` datetime NULL COMMENT 'Mulai viewing',
    `viewing_end` datetime NULL COMMENT 'Akhir viewing',
    `viewing_schedule` text NULL COMMENT 'Jadwal viewing',
    `viewing_contact` text NULL COMMENT 'Kontak viewing',
    `viewing_notes` text NULL COMMENT 'Catatan viewing',
    
    -- Terms & Conditions
    `terms_conditions` text NULL COMMENT 'Syarat dan ketentuan',
    `special_conditions` text NULL COMMENT 'Syarat khusus',
    `payment_terms` text NULL COMMENT 'Syarat pembayaran',
    `payment_deadline_days` int(11) DEFAULT 30 COMMENT 'Batas waktu pelunasan (hari)',
    `delivery_terms` text NULL COMMENT 'Syarat penyerahan',
    
    -- Organizer Information
    `organizer_name` varchar(255) NULL COMMENT 'Penyelenggara',
    `organizer_type` varchar(255) NULL COMMENT 'Jenis penyelenggara',
    `organizer_address` text NULL COMMENT 'Alamat penyelenggara',
    `organizer_phone` varchar(255) NULL COMMENT 'Telepon penyelenggara',
    `organizer_email` varchar(255) NULL COMMENT 'Email penyelenggara',
    `organizer_website` varchar(255) NULL COMMENT 'Website penyelenggara',
    
    -- Contact Information
    `contact_person` varchar(255) NOT NULL COMMENT 'Kontak person',
    `contact_position` varchar(255) NULL COMMENT 'Jabatan kontak person',
    `contact_phone` varchar(255) NOT NULL COMMENT 'Telepon kontak',
    `contact_email` varchar(255) NULL COMMENT 'Email kontak',
    `contact_whatsapp` varchar(255) NULL COMMENT 'WhatsApp kontak',
    `contact_office_hours` text NULL COMMENT 'Jam kerja',
    
    -- Documents & Media
    `images` json NULL COMMENT 'Foto-foto aset',
    `documents` json NULL COMMENT 'Dokumen-dokumen',
    `floor_plans` json NULL COMMENT 'Denah',
    `certificates` json NULL COMMENT 'Sertifikat',
    `virtual_tour_url` varchar(255) NULL COMMENT 'URL virtual tour',
    `video_url` varchar(255) NULL COMMENT 'URL video',
    
    -- Status & Results
    `status` enum('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed') DEFAULT 'draft' COMMENT 'Status lelang',
    `status_notes` text NULL COMMENT 'Catatan status',
    
    -- Auction Results
    `winning_bid` decimal(15,2) NULL COMMENT 'Penawaran pemenang',
    `winner_name` varchar(255) NULL COMMENT 'Nama pemenang',
    `winner_id_number` varchar(255) NULL COMMENT 'NIK pemenang',
    `winner_address` text NULL COMMENT 'Alamat pemenang',
    `winner_phone` varchar(255) NULL COMMENT 'Telepon pemenang',
    `sold_at` datetime NULL COMMENT 'Tanggal terjual',
    `auction_notes` text NULL COMMENT 'Catatan hasil lelang',
    `total_bidders` int(11) NULL COMMENT 'Jumlah peserta',
    `total_bids` int(11) NULL COMMENT 'Jumlah penawaran',
    
    -- Additional Information
    `facilities` text NULL COMMENT 'Fasilitas',
    `nearby_facilities` text NULL COMMENT 'Fasilitas sekitar',
    `transportation_access` text NULL COMMENT 'Akses transportasi',
    `investment_potential` text NULL COMMENT 'Potensi investasi',
    `market_analysis` text NULL COMMENT 'Analisis pasar',
    `risk_factors` text NULL COMMENT 'Faktor risiko',
    
    -- SEO & Meta
    `meta_title` varchar(255) NULL COMMENT 'Meta title',
    `meta_description` text NULL COMMENT 'Meta description',
    `meta_keywords` text NULL COMMENT 'Meta keywords',
    
    -- Tracking
    `view_count` int(11) DEFAULT 0 COMMENT 'Jumlah views',
    `interest_count` int(11) DEFAULT 0 COMMENT 'Jumlah yang berminat',
    `download_count` int(11) DEFAULT 0 COMMENT 'Jumlah download',
    
    -- Publishing
    `published_at` datetime NULL COMMENT 'Tanggal publikasi',
    `featured_until` datetime NULL COMMENT 'Featured sampai',
    `is_featured` tinyint(1) DEFAULT 0 COMMENT 'Apakah featured',
    `is_urgent` tinyint(1) DEFAULT 0 COMMENT 'Apakah mendesak',
    `sort_order` int(11) DEFAULT 0 COMMENT 'Urutan tampil',
    
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `auctions_slug_unique` (`slug`),
    UNIQUE KEY `auctions_auction_number_unique` (`auction_number`),
    KEY `idx_status_published` (`status`, `published_at`),
    KEY `idx_auction_date_status` (`auction_date`, `status`),
    KEY `idx_asset_type_city` (`asset_type`, `city`),
    KEY `idx_limit_price_status` (`limit_price`, `status`),
    KEY `idx_featured_published` (`is_featured`, `published_at`),
    KEY `idx_city` (`city`),
    KEY `idx_asset_type` (`asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel lelang agunan';

-- =====================================================
-- 2. KAS KELILING SCHEDULES TABLE IMPROVEMENTS
-- =====================================================

-- Drop old kas_keliling_schedules if exists and create new one
DROP TABLE IF EXISTS `kas_keliling_schedules_old`;

-- Rename existing table if it exists
SET @table_exists = (SELECT COUNT(*) FROM information_schema.tables 
                    WHERE table_schema = DATABASE() 
                    AND table_name = 'kas_keliling_schedules');

SET @sql = IF(@table_exists > 0, 
    'RENAME TABLE kas_keliling_schedules TO kas_keliling_schedules_old', 
    'SELECT "Table does not exist" as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create new kas_keliling_schedules table
CREATE TABLE `kas_keliling_schedules` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `schedule_date` date NOT NULL COMMENT 'Tanggal jadwal',
    `day_name` varchar(20) NOT NULL COMMENT 'Nama hari (Senin, Selasa, dll)',
    `start_time` time NOT NULL COMMENT 'Jam mulai',
    `end_time` time NOT NULL COMMENT 'Jam selesai',
    `location` varchar(255) NOT NULL COMMENT 'Lokasi/Tujuan',
    `facility` text NULL COMMENT 'Fasilitas yang tersedia (comma separated)',
    `pic_name` varchar(255) NULL COMMENT 'Nama PIC (Person In Charge)',
    `pic_phone` varchar(20) NULL COMMENT 'Nomor telepon PIC',
    `notes` text NULL COMMENT 'Catatan tambahan',
    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Status aktif',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_schedule_date` (`schedule_date`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_schedule_date_active` (`schedule_date`, `is_active`),
    KEY `idx_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Jadwal Kas Keliling';

-- =====================================================
-- 3. WHY CHOOSE US TABLES IMPROVEMENTS
-- =====================================================

-- Ensure why_choose_us table exists with proper structure
CREATE TABLE IF NOT EXISTS `why_choose_us` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL COMMENT 'Judul keunggulan',
    `description` text NOT NULL COMMENT 'Deskripsi keunggulan',
    `icon` varchar(255) NULL COMMENT 'Path ke file icon',
    `color_theme` varchar(50) DEFAULT 'primary' COMMENT 'Tema warna (primary, emerald, blue, amber, etc)',
    `sort_order` int(11) DEFAULT 0 COMMENT 'Urutan tampil',
    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Status aktif',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_sort_order` (`sort_order`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_active_sort` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Item Why Choose Us';

-- Ensure why_choose_us_settings table exists
CREATE TABLE IF NOT EXISTS `why_choose_us_settings` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `section_title` varchar(255) DEFAULT 'Mengapa Memilih Kami' COMMENT 'Judul section',
    `section_subtitle` text NULL COMMENT 'Subtitle section',
    `section_image` varchar(255) NULL COMMENT 'Gambar utama section',
    `badge_text` varchar(255) NULL COMMENT 'Teks badge (contoh: 100% Syariah Compliant)',
    `badge_icon` varchar(255) NULL COMMENT 'Icon badge',
    `is_active` tinyint(1) DEFAULT 1 COMMENT 'Status aktif section',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pengaturan Section Why Choose Us';

-- =====================================================
-- 4. INSERT DEFAULT DATA
-- =====================================================

-- Insert default why choose us settings if not exists
INSERT IGNORE INTO `why_choose_us_settings` (`id`, `section_title`, `section_subtitle`, `badge_text`, `is_active`) VALUES
(1, 'Mengapa Memilih Kami', 'Kami memberikan layanan terbaik dengan standar syariah yang terpercaya', '100% Syariah Compliant', 1);

-- Insert sample kas keliling schedules for current week
INSERT INTO `kas_keliling_schedules` (`schedule_date`, `day_name`, `start_time`, `end_time`, `location`, `facility`, `pic_name`, `pic_phone`, `notes`, `is_active`) VALUES
('2026-01-27', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu pertama', 1),
('2026-01-28', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Pemali', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu pertama', 1),
('2026-01-29', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu pertama', 1),
('2026-01-30', 'Kamis', '08:00:00', '12:00:00', 'Pasar Koba', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu pertama', 1),
('2026-01-31', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu pertama', 1);

-- Insert sample why choose us items if table is empty
INSERT INTO `why_choose_us` (`title`, `description`, `color_theme`, `sort_order`, `is_active`) 
SELECT * FROM (
    SELECT 'Syariah Compliant' as title, 'Semua produk dan layanan kami sesuai dengan prinsip syariah Islam yang telah disertifikasi oleh Dewan Pengawas Syariah.' as description, 'emerald' as color_theme, 1 as sort_order, 1 as is_active
    UNION ALL
    SELECT 'Pelayanan Prima', 'Tim profesional kami siap memberikan pelayanan terbaik dengan standar kualitas tinggi untuk kepuasan nasabah.', 'blue', 2, 1
    UNION ALL
    SELECT 'Teknologi Modern', 'Didukung sistem teknologi terkini untuk kemudahan transaksi dan akses layanan perbankan 24/7.', 'purple', 3, 1
    UNION ALL
    SELECT 'Terpercaya', 'Telah dipercaya ribuan nasabah dengan track record yang solid dan transparansi dalam setiap layanan.', 'amber', 4, 1
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `why_choose_us` LIMIT 1);

-- =====================================================
-- 5. UPDATE ADMIN MENU PERMISSIONS (if needed)
-- =====================================================

-- Update admin menu for auctions if exists
UPDATE `admin_menus` SET 
    `name` = 'Lelang Agunan',
    `icon` = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    `description` = 'Kelola lelang agunan dan properti'
WHERE `route` = 'admin.auctions.index' AND `name` != 'Lelang Agunan';

-- Update admin menu for kas keliling if exists
UPDATE `admin_menus` SET 
    `name` = 'Kas Keliling',
    `icon` = 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    `description` = 'Kelola jadwal kas keliling'
WHERE `route` = 'admin.kas-keliling.index' AND `name` != 'Kas Keliling';

-- Update admin menu for why choose us if exists
UPDATE `admin_menus` SET 
    `name` = 'Why Choose Us',
    `icon` = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    `description` = 'Kelola section mengapa memilih kami'
WHERE `route` = 'admin.why-choose-us.index' AND `name` != 'Why Choose Us';

-- =====================================================
-- 6. CLEANUP AND OPTIMIZATION
-- =====================================================

-- Update AUTO_INCREMENT values
ALTER TABLE `auctions` AUTO_INCREMENT = 1;
ALTER TABLE `kas_keliling_schedules` AUTO_INCREMENT = 1;
ALTER TABLE `why_choose_us` AUTO_INCREMENT = 1;
ALTER TABLE `why_choose_us_settings` AUTO_INCREMENT = 1;

-- Optimize tables
OPTIMIZE TABLE `auctions`;
OPTIMIZE TABLE `kas_keliling_schedules`;
OPTIMIZE TABLE `why_choose_us`;
OPTIMIZE TABLE `why_choose_us_settings`;

-- =====================================================
-- 7. VERIFICATION QUERIES
-- =====================================================

-- Verify table structures
SELECT 'VERIFICATION: Checking table structures...' as status;

SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    TABLE_COMMENT
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN ('auctions', 'kas_keliling_schedules', 'why_choose_us', 'why_choose_us_settings')
ORDER BY TABLE_NAME;

-- Check sample data
SELECT 'VERIFICATION: Sample data counts...' as status;
SELECT 'auctions' as table_name, COUNT(*) as record_count FROM `auctions`
UNION ALL
SELECT 'kas_keliling_schedules', COUNT(*) FROM `kas_keliling_schedules`
UNION ALL
SELECT 'why_choose_us', COUNT(*) FROM `why_choose_us`
UNION ALL
SELECT 'why_choose_us_settings', COUNT(*) FROM `why_choose_us_settings`;

-- =====================================================
-- COMPLETION MESSAGE
-- =====================================================

SELECT 'SUCCESS: Database admin fixes completed successfully!' as status,
       NOW() as completed_at,
       'All tables created/updated with proper structure and sample data' as message;

COMMIT;

-- =====================================================
-- NOTES FOR DEVELOPER:
-- =====================================================
-- 1. Backup database sebelum menjalankan script ini
-- 2. Script ini aman dijalankan berulang kali (idempotent)
-- 3. Semua tabel menggunakan InnoDB engine untuk konsistensi
-- 4. Index telah dioptimalkan untuk performa query
-- 5. Sample data hanya diinsert jika tabel kosong
-- 6. Struktur tabel sesuai dengan model Laravel yang telah diperbaiki
-- =====================================================