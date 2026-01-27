-- =====================================================
-- ADMIN CONFIGURATION UPDATES
-- File: database_admin_configuration_updates.sql
-- Date: 2026-01-27
-- Description: Update konfigurasi admin dan data pendukung
--              untuk perbaikan menu admin
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- 1. UPDATE ADMIN MENU CONFIGURATIONS
-- =====================================================

-- Update menu lelang agunan jika sudah ada
UPDATE `admin_menus` SET 
    `name` = 'Lelang Agunan',
    `icon` = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    `description` = 'Kelola lelang agunan dan properti',
    `updated_at` = NOW()
WHERE `route` = 'admin.auctions.index';

-- Insert menu lelang agunan jika belum ada
INSERT IGNORE INTO `admin_menus` (`name`, `route`, `icon`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('Lelang Agunan', 'admin.auctions.index', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'Kelola lelang agunan dan properti', NULL, 15, 1, NOW(), NOW());

-- Update menu kas keliling jika sudah ada
UPDATE `admin_menus` SET 
    `name` = 'Kas Keliling',
    `icon` = 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    `description` = 'Kelola jadwal kas keliling',
    `updated_at` = NOW()
WHERE `route` = 'admin.kas-keliling.index';

-- Insert menu kas keliling jika belum ada
INSERT IGNORE INTO `admin_menus` (`name`, `route`, `icon`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('Kas Keliling', 'admin.kas-keliling.index', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'Kelola jadwal kas keliling', NULL, 16, 1, NOW(), NOW());

-- Update menu why choose us jika sudah ada
UPDATE `admin_menus` SET 
    `name` = 'Why Choose Us',
    `icon` = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    `description` = 'Kelola section mengapa memilih kami',
    `updated_at` = NOW()
WHERE `route` = 'admin.why-choose-us.index';

-- Insert menu why choose us jika belum ada
INSERT IGNORE INTO `admin_menus` (`name`, `route`, `icon`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('Why Choose Us', 'admin.why-choose-us.index', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'Kelola section mengapa memilih kami', NULL, 17, 1, NOW(), NOW());

-- =====================================================
-- 2. UPDATE SITE SETTINGS FOR ADMIN THEME
-- =====================================================

-- Update site settings untuk konsistensi tema admin
UPDATE `site_settings` SET 
    `value` = 'emerald'
WHERE `key` = 'admin_primary_color' AND `value` != 'emerald';

-- Insert admin theme settings jika belum ada
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('admin_primary_color', 'emerald', 'Warna utama admin panel', NOW(), NOW()),
('admin_theme_consistent', '1', 'Konsistensi tema admin', NOW(), NOW()),
('admin_mobile_responsive', '1', 'Responsif mobile admin', NOW(), NOW());

-- =====================================================
-- 3. UPDATE PERMISSIONS FOR NEW FEATURES
-- =====================================================

-- Insert permissions untuk lelang agunan jika belum ada
INSERT IGNORE INTO `permissions` (`name`, `description`, `created_at`, `updated_at`) VALUES
('auctions.view', 'Lihat daftar lelang agunan', NOW(), NOW()),
('auctions.create', 'Tambah lelang agunan baru', NOW(), NOW()),
('auctions.edit', 'Edit lelang agunan', NOW(), NOW()),
('auctions.delete', 'Hapus lelang agunan', NOW(), NOW()),
('auctions.publish', 'Publikasi lelang agunan', NOW(), NOW()),
('auctions.feature', 'Jadikan lelang unggulan', NOW(), NOW());

-- Insert permissions untuk kas keliling jika belum ada
INSERT IGNORE INTO `permissions` (`name`, `description`, `created_at`, `updated_at`) VALUES
('kas-keliling.view', 'Lihat jadwal kas keliling', NOW(), NOW()),
('kas-keliling.create', 'Tambah jadwal kas keliling', NOW(), NOW()),
('kas-keliling.edit', 'Edit jadwal kas keliling', NOW(), NOW()),
('kas-keliling.delete', 'Hapus jadwal kas keliling', NOW(), NOW());

-- Insert permissions untuk why choose us jika belum ada
INSERT IGNORE INTO `permissions` (`name`, `description`, `created_at`, `updated_at`) VALUES
('why-choose-us.view', 'Lihat why choose us', NOW(), NOW()),
('why-choose-us.create', 'Tambah item why choose us', NOW(), NOW()),
('why-choose-us.edit', 'Edit item why choose us', NOW(), NOW()),
('why-choose-us.delete', 'Hapus item why choose us', NOW(), NOW()),
('why-choose-us.settings', 'Kelola pengaturan why choose us', NOW(), NOW());

-- =====================================================
-- 4. ASSIGN PERMISSIONS TO SUPER ADMIN ROLE
-- =====================================================

-- Get super admin role ID
SET @super_admin_role_id = (SELECT id FROM roles WHERE name = 'Super Admin' OR name = 'super_admin' LIMIT 1);

-- Assign auction permissions to super admin
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT @super_admin_role_id, p.id, NOW(), NOW()
FROM `permissions` p 
WHERE p.name LIKE 'auctions.%' AND @super_admin_role_id IS NOT NULL;

-- Assign kas keliling permissions to super admin
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT @super_admin_role_id, p.id, NOW(), NOW()
FROM `permissions` p 
WHERE p.name LIKE 'kas-keliling.%' AND @super_admin_role_id IS NOT NULL;

-- Assign why choose us permissions to super admin
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
SELECT @super_admin_role_id, p.id, NOW(), NOW()
FROM `permissions` p 
WHERE p.name LIKE 'why-choose-us.%' AND @super_admin_role_id IS NOT NULL;

-- =====================================================
-- 5. UPDATE COMPANY INFO FOR CONSISTENT BRANDING
-- =====================================================

-- Update company info untuk mendukung fitur baru
UPDATE `company_infos` SET 
    `updated_at` = NOW()
WHERE id = 1;

-- Pastikan ada setting untuk auction
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('auction_enabled', '1', 'Aktifkan fitur lelang agunan', NOW(), NOW()),
('auction_auto_publish', '0', 'Auto publish lelang baru', NOW(), NOW()),
('auction_featured_limit', '5', 'Batas lelang unggulan', NOW(), NOW()),
('kas_keliling_enabled', '1', 'Aktifkan fitur kas keliling', NOW(), NOW()),
('kas_keliling_auto_schedule', '1', 'Auto generate jadwal kas keliling', NOW(), NOW()),
('why_choose_us_max_items', '6', 'Maksimal item why choose us', NOW(), NOW());

-- =====================================================
-- 6. UPDATE CACHE SETTINGS
-- =====================================================

-- Clear cache settings yang mungkin perlu di-refresh
DELETE FROM `cache` WHERE `key` LIKE 'admin_menu_%';
DELETE FROM `cache` WHERE `key` LIKE 'auction_%';
DELETE FROM `cache` WHERE `key` LIKE 'kas_keliling_%';
DELETE FROM `cache` WHERE `key` LIKE 'why_choose_us_%';

-- =====================================================
-- 7. UPDATE AUDIT TRAIL SETTINGS
-- =====================================================

-- Pastikan audit trail mencakup tabel baru
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('audit_auctions', '1', 'Audit trail untuk lelang agunan', NOW(), NOW()),
('audit_kas_keliling', '1', 'Audit trail untuk kas keliling', NOW(), NOW()),
('audit_why_choose_us', '1', 'Audit trail untuk why choose us', NOW(), NOW());

-- =====================================================
-- 8. SAMPLE DATA UPDATES
-- =====================================================

-- Update sample auction data jika ada
UPDATE `auctions` SET 
    `status` = 'published',
    `published_at` = NOW(),
    `updated_at` = NOW()
WHERE `status` = 'draft' AND `auction_date` > NOW();

-- Update kas keliling schedules untuk minggu depan
INSERT INTO `kas_keliling_schedules` (`schedule_date`, `day_name`, `start_time`, `end_time`, `location`, `facility`, `pic_name`, `pic_phone`, `notes`, `is_active`) VALUES
('2026-02-03', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu kedua', 1),
('2026-02-04', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Pemali', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu kedua', 1),
('2026-02-05', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu kedua', 1),
('2026-02-06', 'Kamis', '08:00:00', '12:00:00', 'Pasar Koba', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu kedua', 1),
('2026-02-07', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu kedua', 1)
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- =====================================================
-- 9. STORAGE DIRECTORY SETUP
-- =====================================================

-- Insert storage settings untuk file uploads
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('storage_auction_images', 'auctions/images', 'Direktori gambar lelang', NOW(), NOW()),
('storage_auction_documents', 'auctions/documents', 'Direktori dokumen lelang', NOW(), NOW()),
('storage_why_choose_us_icons', 'why-choose-us/icons', 'Direktori icon why choose us', NOW(), NOW()),
('storage_why_choose_us_images', 'why-choose-us/images', 'Direktori gambar why choose us', NOW(), NOW()),
('max_upload_size_auction', '10240', 'Maksimal upload lelang (KB)', NOW(), NOW()),
('max_upload_size_icon', '2048', 'Maksimal upload icon (KB)', NOW(), NOW()),
('allowed_image_types', 'jpg,jpeg,png,webp', 'Tipe gambar yang diizinkan', NOW(), NOW()),
('allowed_document_types', 'pdf,doc,docx', 'Tipe dokumen yang diizinkan', NOW(), NOW());

-- =====================================================
-- 10. EMAIL NOTIFICATION SETTINGS
-- =====================================================

-- Insert email template settings
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('email_auction_created', '1', 'Email notifikasi lelang baru', NOW(), NOW()),
('email_auction_published', '1', 'Email notifikasi lelang dipublikasi', NOW(), NOW()),
('email_kas_keliling_reminder', '1', 'Email reminder kas keliling', NOW(), NOW()),
('email_admin_updates', '1', 'Email update admin', NOW(), NOW());

-- =====================================================
-- VERIFICATION AND CLEANUP
-- =====================================================

-- Verify menu updates
SELECT 'VERIFICATION: Admin menu updates' as status;
SELECT name, route, icon, is_active 
FROM admin_menus 
WHERE route IN ('admin.auctions.index', 'admin.kas-keliling.index', 'admin.why-choose-us.index')
ORDER BY sort_order;

-- Verify permissions
SELECT 'VERIFICATION: Permission assignments' as status;
SELECT p.name, r.name as role_name
FROM permissions p
JOIN role_permissions rp ON p.id = rp.permission_id
JOIN roles r ON rp.role_id = r.id
WHERE p.name LIKE 'auctions.%' OR p.name LIKE 'kas-keliling.%' OR p.name LIKE 'why-choose-us.%'
ORDER BY p.name;

-- Verify settings
SELECT 'VERIFICATION: Site settings' as status;
SELECT `key`, `value`, `description`
FROM site_settings 
WHERE `key` LIKE 'auction_%' OR `key` LIKE 'kas_keliling_%' OR `key` LIKE 'why_choose_us_%' OR `key` LIKE 'admin_%'
ORDER BY `key`;

-- Clean up old cache entries
DELETE FROM `cache` WHERE `key` LIKE '%_old' OR `key` LIKE 'temp_%';

-- Update statistics
UPDATE `site_settings` SET 
    `value` = (SELECT COUNT(*) FROM auctions WHERE status != 'draft'),
    `updated_at` = NOW()
WHERE `key` = 'total_published_auctions';

INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('total_published_auctions', (SELECT COUNT(*) FROM auctions WHERE status != 'draft'), 'Total lelang yang dipublikasi', NOW(), NOW()),
('total_kas_keliling_schedules', (SELECT COUNT(*) FROM kas_keliling_schedules WHERE is_active = 1), 'Total jadwal kas keliling aktif', NOW(), NOW()),
('total_why_choose_us_items', (SELECT COUNT(*) FROM why_choose_us WHERE is_active = 1), 'Total item why choose us aktif', NOW(), NOW());

-- =====================================================
-- COMPLETION MESSAGE
-- =====================================================

SELECT 'SUCCESS: Admin configuration updates completed!' as status,
       NOW() as completed_at,
       'All admin configurations, permissions, and settings updated successfully' as message;

COMMIT;

-- =====================================================
-- POST-EXECUTION NOTES:
-- =====================================================
-- 1. Restart web server untuk memastikan cache ter-refresh
-- 2. Login ulang ke admin panel untuk melihat perubahan menu
-- 3. Test semua fitur CRUD pada ketiga modul
-- 4. Verify file upload functionality
-- 5. Check frontend display untuk semua data
-- =====================================================