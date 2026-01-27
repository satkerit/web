-- =====================================================
-- ROLLBACK SCRIPT FOR ADMIN IMPROVEMENTS
-- File: database_rollback_admin_fixes.sql
-- Date: 2026-01-27
-- Description: Script untuk rollback perubahan admin
--              jika terjadi masalah atau perlu dikembalikan
-- =====================================================

-- ⚠️  WARNING: SCRIPT INI AKAN MENGHAPUS DATA!
-- Pastikan Anda sudah backup database sebelum menjalankan script ini
-- Script ini hanya untuk emergency rollback

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- CONFIRMATION CHECK
-- =====================================================

-- Uncomment baris berikut untuk mengkonfirmasi rollback
-- SET @CONFIRM_ROLLBACK = 'YES_I_WANT_TO_ROLLBACK';

-- Safety check
SELECT CASE 
    WHEN @CONFIRM_ROLLBACK = 'YES_I_WANT_TO_ROLLBACK' THEN 'PROCEEDING WITH ROLLBACK...'
    ELSE 'ROLLBACK CANCELLED - Please set @CONFIRM_ROLLBACK variable to proceed'
END as rollback_status;

-- Stop execution if not confirmed
SET @proceed = (SELECT CASE WHEN @CONFIRM_ROLLBACK = 'YES_I_WANT_TO_ROLLBACK' THEN 1 ELSE 0 END);

-- =====================================================
-- 1. BACKUP CURRENT DATA (OPTIONAL)
-- =====================================================

-- Create backup tables before rollback
CREATE TABLE IF NOT EXISTS `auctions_backup_rollback` AS SELECT * FROM `auctions`;
CREATE TABLE IF NOT EXISTS `kas_keliling_schedules_backup_rollback` AS SELECT * FROM `kas_keliling_schedules`;
CREATE TABLE IF NOT EXISTS `why_choose_us_backup_rollback` AS SELECT * FROM `why_choose_us`;
CREATE TABLE IF NOT EXISTS `why_choose_us_settings_backup_rollback` AS SELECT * FROM `why_choose_us_settings`;

SELECT 'Backup tables created' as backup_status;

-- =====================================================
-- 2. REMOVE SAMPLE DATA
-- =====================================================

-- Remove sample auction data (keep real data)
DELETE FROM `auctions` 
WHERE `auction_number` IN ('LA-2026-001', 'LA-2026-002', 'LA-2026-003', 'LA-2026-004', 'LA-2026-005')
AND @proceed = 1;

-- Remove sample kas keliling schedules (keep real data)
DELETE FROM `kas_keliling_schedules` 
WHERE `notes` LIKE '%Jadwal rutin minggu%'
AND @proceed = 1;

-- Remove sample why choose us items (keep real data)
DELETE FROM `why_choose_us` 
WHERE `title` IN (
    'Syariah Compliant 100%',
    'Pelayanan Prima', 
    'Teknologi Modern',
    'Terpercaya & Aman',
    'Jaringan Luas',
    'Produk Beragam'
)
AND @proceed = 1;

-- Reset why choose us settings to default
UPDATE `why_choose_us_settings` SET 
    `section_title` = 'Mengapa Memilih Kami',
    `section_subtitle` = NULL,
    `section_image` = NULL,
    `badge_text` = NULL,
    `badge_icon` = NULL,
    `is_active` = 1,
    `updated_at` = NOW()
WHERE id = 1 AND @proceed = 1;

-- =====================================================
-- 3. REMOVE ADMIN MENU ENTRIES
-- =====================================================

-- Remove admin menu entries for new features
DELETE FROM `admin_menus` 
WHERE `route` IN (
    'admin.auctions.index',
    'admin.kas-keliling.index', 
    'admin.why-choose-us.index'
)
AND @proceed = 1;

-- =====================================================
-- 4. REMOVE PERMISSIONS
-- =====================================================

-- Remove role permissions first
DELETE rp FROM `role_permissions` rp
JOIN `permissions` p ON rp.permission_id = p.id
WHERE p.name LIKE 'auctions.%' 
   OR p.name LIKE 'kas-keliling.%' 
   OR p.name LIKE 'why-choose-us.%'
AND @proceed = 1;

-- Remove permissions
DELETE FROM `permissions` 
WHERE `name` LIKE 'auctions.%' 
   OR `name` LIKE 'kas-keliling.%' 
   OR `name` LIKE 'why-choose-us.%'
AND @proceed = 1;

-- =====================================================
-- 5. REMOVE SITE SETTINGS
-- =====================================================

-- Remove site settings related to new features
DELETE FROM `site_settings` 
WHERE `key` LIKE 'auction_%' 
   OR `key` LIKE 'kas_keliling_%' 
   OR `key` LIKE 'why_choose_us_%'
   OR `key` LIKE 'admin_primary_color'
   OR `key` LIKE 'admin_theme_%'
   OR `key` LIKE 'storage_%'
   OR `key` LIKE 'email_%'
   OR `key` LIKE 'total_%'
   OR `key` LIKE 'last_%'
AND @proceed = 1;

-- =====================================================
-- 6. REMOVE SAMPLE USERS
-- =====================================================

-- Remove sample admin users
DELETE FROM `users` 
WHERE `email` IN (
    'admin.lelang@bprsbabel.com',
    'admin.kaskeliling@bprsbabel.com',
    'admin.content@bprsbabel.com'
)
AND @proceed = 1;

-- =====================================================
-- 7. CLEAN AUDIT TRAIL
-- =====================================================

-- Remove audit trail entries for removed models
DELETE FROM `audit_trails` 
WHERE `model_type` IN (
    'App\\Models\\Auction',
    'App\\Models\\KasKelilingSchedule', 
    'App\\Models\\WhyChooseUs',
    'App\\Models\\WhyChooseUsSetting'
)
AND @proceed = 1;

-- =====================================================
-- 8. CLEAN VISITOR LOGS
-- =====================================================

-- Remove visitor logs for auction and kas keliling pages
DELETE FROM `visitor_logs` 
WHERE `page_visited` LIKE '/auctions%' 
   OR `page_visited` LIKE '/products/kas-keliling%'
AND @proceed = 1;

-- =====================================================
-- 9. CLEAR CACHE
-- =====================================================

-- Clear all cache entries
DELETE FROM `cache` 
WHERE `key` LIKE 'admin_%' 
   OR `key` LIKE 'auction_%' 
   OR `key` LIKE 'kas_keliling_%' 
   OR `key` LIKE 'why_choose_us_%'
AND @proceed = 1;

-- =====================================================
-- 10. OPTIONAL: DROP TABLES COMPLETELY
-- =====================================================

-- Uncomment the following lines if you want to completely remove the tables
-- WARNING: This will permanently delete all data!

-- DROP TABLE IF EXISTS `auctions`;
-- DROP TABLE IF EXISTS `kas_keliling_schedules`;  
-- DROP TABLE IF EXISTS `why_choose_us`;
-- DROP TABLE IF EXISTS `why_choose_us_settings`;

-- =====================================================
-- 11. RESET AUTO INCREMENT
-- =====================================================

-- Reset auto increment for remaining tables
SET @sql = IF(@proceed = 1 AND (SELECT COUNT(*) FROM `auctions`) = 0, 
    'ALTER TABLE `auctions` AUTO_INCREMENT = 1', 
    'SELECT "Auctions table not reset" as message');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(@proceed = 1 AND (SELECT COUNT(*) FROM `kas_keliling_schedules`) = 0, 
    'ALTER TABLE `kas_keliling_schedules` AUTO_INCREMENT = 1', 
    'SELECT "Kas keliling schedules table not reset" as message');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(@proceed = 1 AND (SELECT COUNT(*) FROM `why_choose_us`) = 0, 
    'ALTER TABLE `why_choose_us` AUTO_INCREMENT = 1', 
    'SELECT "Why choose us table not reset" as message');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =====================================================
-- 12. VERIFICATION AFTER ROLLBACK
-- =====================================================

-- Verify rollback results
SELECT 'ROLLBACK VERIFICATION:' as status;

SELECT 
    'auctions' as table_name,
    COUNT(*) as remaining_records
FROM `auctions`
UNION ALL
SELECT 
    'kas_keliling_schedules',
    COUNT(*)
FROM `kas_keliling_schedules`
UNION ALL
SELECT 
    'why_choose_us',
    COUNT(*)
FROM `why_choose_us`
UNION ALL
SELECT 
    'why_choose_us_settings',
    COUNT(*)
FROM `why_choose_us_settings`
UNION ALL
SELECT 
    'admin_menus (auction related)',
    COUNT(*)
FROM `admin_menus`
WHERE `route` IN ('admin.auctions.index', 'admin.kas-keliling.index', 'admin.why-choose-us.index')
UNION ALL
SELECT 
    'permissions (feature related)',
    COUNT(*)
FROM `permissions`
WHERE `name` LIKE 'auctions.%' OR `name` LIKE 'kas-keliling.%' OR `name` LIKE 'why-choose-us.%'
UNION ALL
SELECT 
    'site_settings (feature related)',
    COUNT(*)
FROM `site_settings`
WHERE `key` LIKE 'auction_%' OR `key` LIKE 'kas_keliling_%' OR `key` LIKE 'why_choose_us_%';

-- =====================================================
-- 13. RESTORE ORIGINAL KAS KELILING TABLE (IF EXISTS)
-- =====================================================

-- Restore original kas_keliling_schedules if backup exists
SET @backup_exists = (SELECT COUNT(*) FROM information_schema.tables 
                     WHERE table_schema = DATABASE() 
                     AND table_name = 'kas_keliling_schedules_old');

SET @sql = IF(@backup_exists > 0 AND @proceed = 1, 
    'DROP TABLE IF EXISTS kas_keliling_schedules; RENAME TABLE kas_keliling_schedules_old TO kas_keliling_schedules', 
    'SELECT "No backup table to restore" as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- COMPLETION MESSAGE
-- =====================================================

SELECT CASE 
    WHEN @proceed = 1 THEN 'SUCCESS: Rollback completed successfully!'
    ELSE 'ROLLBACK CANCELLED: Confirmation variable not set'
END as final_status,
NOW() as completed_at,
CASE 
    WHEN @proceed = 1 THEN 'All admin improvements have been rolled back. Please restart your web server.'
    ELSE 'No changes were made. Set @CONFIRM_ROLLBACK = "YES_I_WANT_TO_ROLLBACK" to proceed.'
END as message;

-- Commit only if rollback was confirmed
SET @commit_rollback = IF(@proceed = 1, 'COMMIT', 'ROLLBACK');
SET @sql = @commit_rollback;
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- POST-ROLLBACK INSTRUCTIONS
-- =====================================================

/*
SETELAH MENJALANKAN ROLLBACK:

1. RESTART WEB SERVER
   - Restart Apache/Nginx
   - Restart PHP-FPM jika menggunakan

2. CLEAR APPLICATION CACHE
   - php artisan cache:clear
   - php artisan config:clear
   - php artisan route:clear
   - php artisan view:clear

3. REMOVE FILES (MANUAL)
   - Hapus file view yang telah dibuat:
     * resources/views/admin/auctions/
     * resources/views/admin/kas-keliling/ (yang baru)
     * resources/views/admin/why-choose-us/
   - Hapus file controller yang baru (jika ada)
   - Hapus file model yang baru (jika ada)

4. RESTORE ORIGINAL FILES
   - Restore file asli dari backup jika ada
   - Atau checkout dari git commit sebelumnya

5. VERIFY FUNCTIONALITY
   - Test login admin
   - Test menu yang tersisa
   - Test frontend functionality

6. CLEANUP BACKUP TABLES (OPTIONAL)
   - DROP TABLE auctions_backup_rollback;
   - DROP TABLE kas_keliling_schedules_backup_rollback;
   - DROP TABLE why_choose_us_backup_rollback;
   - DROP TABLE why_choose_us_settings_backup_rollback;

CATATAN PENTING:
- Script ini hanya menghapus data dari database
- File PHP, Blade template, dan asset lainnya harus dihapus manual
- Pastikan backup lengkap tersedia sebelum rollback
- Test semua functionality setelah rollback
*/