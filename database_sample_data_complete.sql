-- =====================================================
-- SAMPLE DATA FOR ADMIN IMPROVEMENTS
-- File: database_sample_data_complete.sql
-- Date: 2026-01-27
-- Description: Data sample lengkap untuk testing
--              fitur admin yang telah diperbaiki
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- 1. SAMPLE AUCTION DATA
-- =====================================================

-- Insert sample auction data
INSERT INTO `auctions` (
    `title`, `slug`, `description`, `auction_number`, `asset_type`, `address`, `city`, `province`,
    `auction_type`, `auction_date`, `auction_location`, `limit_price`, `estimated_price`, 
    `deposit_amount`, `deposit_percentage`, `contact_person`, `contact_phone`, 
    `status`, `published_at`, `is_featured`, `created_at`, `updated_at`
) VALUES
(
    'Rumah Mewah 2 Lantai di Sungailiat',
    'rumah-mewah-2-lantai-di-sungailiat',
    'Rumah mewah 2 lantai dengan fasilitas lengkap, lokasi strategis di pusat kota Sungailiat. Kondisi bangunan terawat dengan baik, cocok untuk investasi atau hunian.',
    'LA-2026-001',
    'rumah',
    'Jl. Jenderal Sudirman No. 123, Sungailiat, Bangka',
    'Sungailiat',
    'Kepulauan Bangka Belitung',
    'eksekusi_hak_tanggungan',
    '2026-02-15 10:00:00',
    'Kantor BPRS Bangka Belitung',
    850000000.00,
    1000000000.00,
    170000000.00,
    20.00,
    'Budi Santoso',
    '0717-123456',
    'published',
    NOW(),
    1,
    NOW(),
    NOW()
),
(
    'Ruko 3 Lantai di Pangkalpinang',
    'ruko-3-lantai-di-pangkalpinang',
    'Ruko 3 lantai di lokasi komersial strategis Pangkalpinang. Cocok untuk usaha retail, kantor, atau investasi properti komersial.',
    'LA-2026-002',
    'ruko',
    'Jl. Ahmad Yani No. 45, Pangkalpinang',
    'Pangkalpinang',
    'Kepulauan Bangka Belitung',
    'eksekusi_fidusia',
    '2026-02-20 14:00:00',
    'Kantor BPRS Bangka Belitung',
    1200000000.00,
    1400000000.00,
    240000000.00,
    20.00,
    'Siti Aminah',
    '0717-234567',
    'published',
    NOW(),
    1,
    NOW(),
    NOW()
),
(
    'Tanah Kavling Siap Bangun di Belinyu',
    'tanah-kavling-siap-bangun-di-belinyu',
    'Tanah kavling siap bangun dengan sertifikat SHM, lokasi strategis dekat dengan fasilitas umum dan akses jalan yang baik.',
    'LA-2026-003',
    'tanah',
    'Jl. Raya Belinyu KM 15, Belinyu, Bangka',
    'Belinyu',
    'Kepulauan Bangka Belitung',
    'non_eksekusi_sukarela',
    '2026-02-25 09:00:00',
    'Kantor Kecamatan Belinyu',
    450000000.00,
    500000000.00,
    90000000.00,
    20.00,
    'Ahmad Yani',
    '0717-345678',
    'registration_open',
    NOW(),
    0,
    NOW(),
    NOW()
),
(
    'Apartemen 2 Bedroom di Mentok',
    'apartemen-2-bedroom-di-mentok',
    'Apartemen modern 2 bedroom dengan view laut, fasilitas lengkap termasuk kolam renang, gym, dan security 24 jam.',
    'LA-2026-004',
    'apartemen',
    'Jl. Pantai Mentok No. 88, Mentok, Bangka Barat',
    'Mentok',
    'Kepulauan Bangka Belitung',
    'eksekusi_hak_tanggungan',
    '2026-03-01 11:00:00',
    'Hotel Mentok Beach Resort',
    650000000.00,
    750000000.00,
    130000000.00,
    20.00,
    'Dewi Lestari',
    '0717-456789',
    'auction_scheduled',
    NOW(),
    0,
    NOW(),
    NOW()
),
(
    'Gedung Perkantoran 4 Lantai di Toboali',
    'gedung-perkantoran-4-lantai-di-toboali',
    'Gedung perkantoran 4 lantai dengan fasilitas lengkap, parkir luas, dan lokasi strategis di pusat bisnis Toboali.',
    'LA-2026-005',
    'gedung',
    'Jl. Merdeka No. 200, Toboali, Bangka Selatan',
    'Toboali',
    'Kepulauan Bangka Belitung',
    'eksekusi_hipotik',
    '2026-03-05 13:00:00',
    'Kantor Bupati Bangka Selatan',
    2500000000.00,
    3000000000.00,
    500000000.00,
    20.00,
    'Eko Prasetyo',
    '0717-567890',
    'draft',
    NULL,
    0,
    NOW(),
    NOW()
);

-- =====================================================
-- 2. SAMPLE KAS KELILING SCHEDULES (EXTENDED)
-- =====================================================

-- Insert extended kas keliling schedules for next 4 weeks
INSERT INTO `kas_keliling_schedules` (
    `schedule_date`, `day_name`, `start_time`, `end_time`, `location`, 
    `facility`, `pic_name`, `pic_phone`, `notes`, `is_active`
) VALUES
-- Week 1 (Current week)
('2026-01-27', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu pertama', 1),
('2026-01-28', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Pemali', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu pertama', 1),
('2026-01-29', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu pertama', 1),
('2026-01-30', 'Kamis', '08:00:00', '12:00:00', 'Pasar Koba', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu pertama', 1),
('2026-01-31', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu pertama', 1),

-- Week 2
('2026-02-03', 'Senin', '08:00:00', '12:00:00', 'Pasar Mentok', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu kedua', 1),
('2026-02-04', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Jebus', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu kedua', 1),
('2026-02-05', 'Rabu', '08:30:00', '12:30:00', 'Pasar Toboali', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu kedua', 1),
('2026-02-06', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pangkalpinang', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu kedua', 1),
('2026-02-07', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Bukit Intan', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu kedua', 1),

-- Week 3
('2026-02-10', 'Senin', '08:00:00', '12:00:00', 'Pasar Klandasan', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu ketiga', 1),
('2026-02-11', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sinar Baru', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu ketiga', 1),
('2026-02-12', 'Rabu', '08:30:00', '12:30:00', 'Pasar Simpang Rimba', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu ketiga', 1),
('2026-02-13', 'Kamis', '08:00:00', '12:00:00', 'Pasar Kelapa', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu ketiga', 1),
('2026-02-14', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Riau Silip', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu ketiga', 1),

-- Week 4
('2026-02-17', 'Senin', '08:00:00', '12:00:00', 'Pasar Tempilang', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin minggu keempat', 1),
('2026-02-18', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Kurau', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin minggu keempat', 1),
('2026-02-19', 'Rabu', '08:30:00', '12:30:00', 'Pasar Bakam', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin minggu keempat', 1),
('2026-02-20', 'Kamis', '08:00:00', '12:00:00', 'Pasar Merawang', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin minggu keempat', 1),
('2026-02-21', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Puding Besar', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin minggu keempat', 1);

-- =====================================================
-- 3. SAMPLE WHY CHOOSE US DATA
-- =====================================================

-- Clear existing data and insert comprehensive why choose us items
DELETE FROM `why_choose_us` WHERE id > 0;

INSERT INTO `why_choose_us` (
    `title`, `description`, `color_theme`, `sort_order`, `is_active`, `created_at`, `updated_at`
) VALUES
(
    'Syariah Compliant 100%',
    'Semua produk dan layanan kami sesuai dengan prinsip syariah Islam yang telah disertifikasi oleh Dewan Pengawas Syariah dan diawasi ketat oleh OJK.',
    'emerald',
    1,
    1,
    NOW(),
    NOW()
),
(
    'Pelayanan Prima',
    'Tim profesional kami siap memberikan pelayanan terbaik dengan standar kualitas tinggi, responsif, dan ramah untuk kepuasan nasabah.',
    'blue',
    2,
    1,
    NOW(),
    NOW()
),
(
    'Teknologi Modern',
    'Didukung sistem teknologi terkini untuk kemudahan transaksi dan akses layanan perbankan 24/7 melalui mobile banking dan internet banking.',
    'purple',
    3,
    1,
    NOW(),
    NOW()
),
(
    'Terpercaya & Aman',
    'Telah dipercaya ribuan nasabah dengan track record yang solid, transparansi dalam setiap layanan, dan keamanan data yang terjamin.',
    'amber',
    4,
    1,
    NOW(),
    NOW()
),
(
    'Jaringan Luas',
    'Memiliki jaringan kantor cabang dan kas keliling yang tersebar luas di seluruh Kepulauan Bangka Belitung untuk kemudahan akses nasabah.',
    'rose',
    5,
    1,
    NOW(),
    NOW()
),
(
    'Produk Beragam',
    'Menyediakan berbagai produk perbankan syariah mulai dari tabungan, deposito, pembiayaan, hingga layanan investasi yang sesuai kebutuhan.',
    'indigo',
    6,
    1,
    NOW(),
    NOW()
);

-- =====================================================
-- 4. UPDATE WHY CHOOSE US SETTINGS
-- =====================================================

-- Update why choose us settings with complete data
UPDATE `why_choose_us_settings` SET 
    `section_title` = 'Mengapa Memilih BPRS Bangka Belitung',
    `section_subtitle` = 'Kami berkomitmen memberikan layanan perbankan syariah terbaik dengan prinsip amanah, profesional, dan mengutamakan kepuasan nasabah',
    `badge_text` = '100% Syariah Compliant',
    `is_active` = 1,
    `updated_at` = NOW()
WHERE id = 1;

-- Insert if not exists
INSERT IGNORE INTO `why_choose_us_settings` (
    `id`, `section_title`, `section_subtitle`, `badge_text`, `is_active`, `created_at`, `updated_at`
) VALUES (
    1,
    'Mengapa Memilih BPRS Bangka Belitung',
    'Kami berkomitmen memberikan layanan perbankan syariah terbaik dengan prinsip amanah, profesional, dan mengutamakan kepuasan nasabah',
    '100% Syariah Compliant',
    1,
    NOW(),
    NOW()
);

-- =====================================================
-- 5. SAMPLE ADMIN USERS FOR TESTING
-- =====================================================

-- Insert sample admin users if not exists (password: 'password123')
INSERT IGNORE INTO `users` (
    `name`, `email`, `email_verified_at`, `password`, `role`, `created_at`, `updated_at`
) VALUES
(
    'Admin Lelang',
    'admin.lelang@bprsbabel.com',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    'admin',
    NOW(),
    NOW()
),
(
    'Admin Kas Keliling',
    'admin.kaskeliling@bprsbabel.com',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    'admin',
    NOW(),
    NOW()
),
(
    'Admin Content',
    'admin.content@bprsbabel.com',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    'admin',
    NOW(),
    NOW()
);

-- =====================================================
-- 6. SAMPLE AUDIT TRAIL DATA
-- =====================================================

-- Insert sample audit trail entries
INSERT INTO `audit_trails` (
    `user_id`, `model_type`, `model_id`, `action`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`
) VALUES
(
    1,
    'App\\Models\\Auction',
    1,
    'created',
    NULL,
    '{"title":"Rumah Mewah 2 Lantai di Sungailiat","status":"draft"}',
    '127.0.0.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    NOW()
),
(
    1,
    'App\\Models\\Auction',
    1,
    'updated',
    '{"status":"draft"}',
    '{"status":"published","published_at":"2026-01-27 10:00:00"}',
    '127.0.0.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    NOW()
),
(
    2,
    'App\\Models\\KasKelilingSchedule',
    1,
    'created',
    NULL,
    '{"location":"Pasar Pagi Sungailiat","schedule_date":"2026-01-27"}',
    '127.0.0.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    NOW()
),
(
    3,
    'App\\Models\\WhyChooseUs',
    1,
    'created',
    NULL,
    '{"title":"Syariah Compliant 100%","is_active":true}',
    '127.0.0.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    NOW()
);

-- =====================================================
-- 7. UPDATE STATISTICS
-- =====================================================

-- Update site statistics
UPDATE `site_settings` SET 
    `value` = (SELECT COUNT(*) FROM auctions WHERE status IN ('published', 'registration_open', 'auction_scheduled')),
    `updated_at` = NOW()
WHERE `key` = 'total_published_auctions';

UPDATE `site_settings` SET 
    `value` = (SELECT COUNT(*) FROM kas_keliling_schedules WHERE is_active = 1 AND schedule_date >= CURDATE()),
    `updated_at` = NOW()
WHERE `key` = 'total_kas_keliling_schedules';

UPDATE `site_settings` SET 
    `value` = (SELECT COUNT(*) FROM why_choose_us WHERE is_active = 1),
    `updated_at` = NOW()
WHERE `key` = 'total_why_choose_us_items';

-- Insert new statistics
INSERT IGNORE INTO `site_settings` (`key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('total_active_auctions', (SELECT COUNT(*) FROM auctions WHERE status IN ('published', 'registration_open', 'auction_scheduled')), 'Total lelang aktif', NOW(), NOW()),
('total_upcoming_kas_keliling', (SELECT COUNT(*) FROM kas_keliling_schedules WHERE is_active = 1 AND schedule_date >= CURDATE()), 'Total jadwal kas keliling mendatang', NOW(), NOW()),
('last_auction_update', NOW(), 'Update terakhir data lelang', NOW(), NOW()),
('last_kas_keliling_update', NOW(), 'Update terakhir kas keliling', NOW(), NOW()),
('last_why_choose_us_update', NOW(), 'Update terakhir why choose us', NOW(), NOW());

-- =====================================================
-- 8. SAMPLE VISITOR LOGS
-- =====================================================

-- Insert sample visitor logs for analytics
INSERT INTO `visitor_logs` (
    `ip_address`, `user_agent`, `page_visited`, `referrer`, `session_id`, `created_at`
) VALUES
('192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/auctions', 'https://google.com', 'sess_001', NOW()),
('192.168.1.101', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '/auctions/rumah-mewah-2-lantai-di-sungailiat', '/auctions', 'sess_002', NOW()),
('192.168.1.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15', '/products/kas-keliling', 'https://facebook.com', 'sess_003', NOW()),
('192.168.1.103', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0', '/', 'direct', 'sess_004', NOW()),
('192.168.1.104', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0', '/auctions/ruko-3-lantai-di-pangkalpinang', '/auctions', 'sess_005', NOW());

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Verify sample data insertion
SELECT 'VERIFICATION: Sample data counts' as status;

SELECT 'auctions' as table_name, COUNT(*) as total_records, 
       SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_count,
       SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_count
FROM auctions
UNION ALL
SELECT 'kas_keliling_schedules', COUNT(*), 
       SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END),
       SUM(CASE WHEN schedule_date >= CURDATE() THEN 1 ELSE 0 END)
FROM kas_keliling_schedules
UNION ALL
SELECT 'why_choose_us', COUNT(*), 
       SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END),
       0
FROM why_choose_us
UNION ALL
SELECT 'why_choose_us_settings', COUNT(*), 
       SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END),
       0
FROM why_choose_us_settings;

-- Show sample auction data
SELECT 'SAMPLE AUCTIONS:' as info;
SELECT id, title, auction_number, asset_type, city, status, is_featured, created_at
FROM auctions 
ORDER BY created_at DESC 
LIMIT 5;

-- Show sample kas keliling schedules
SELECT 'SAMPLE KAS KELILING SCHEDULES:' as info;
SELECT id, schedule_date, day_name, location, pic_name, is_active
FROM kas_keliling_schedules 
WHERE schedule_date >= CURDATE()
ORDER BY schedule_date ASC 
LIMIT 10;

-- Show why choose us items
SELECT 'SAMPLE WHY CHOOSE US ITEMS:' as info;
SELECT id, title, color_theme, sort_order, is_active
FROM why_choose_us 
ORDER BY sort_order ASC;

-- Show settings
SELECT 'WHY CHOOSE US SETTINGS:' as info;
SELECT section_title, section_subtitle, badge_text, is_active
FROM why_choose_us_settings 
WHERE id = 1;

-- =====================================================
-- COMPLETION MESSAGE
-- =====================================================

SELECT 'SUCCESS: Sample data insertion completed!' as status,
       NOW() as completed_at,
       'All sample data for auctions, kas keliling, and why choose us inserted successfully' as message;

COMMIT;

-- =====================================================
-- TESTING CHECKLIST:
-- =====================================================
-- □ Login ke admin panel
-- □ Test CRUD operations pada Lelang Agunan
-- □ Test CRUD operations pada Kas Keliling
-- □ Test CRUD operations pada Why Choose Us
-- □ Test upload gambar pada semua modul
-- □ Verify frontend display untuk semua data
-- □ Test search dan filtering
-- □ Test responsive design pada mobile
-- □ Verify audit trail logging
-- □ Test permissions dan role access
-- =====================================================