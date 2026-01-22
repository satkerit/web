-- =====================================================
-- SQL INSERT DATA MASTER KAS KELILING
-- File ini berisi data master area kas keliling
-- Jalankan file ini SEBELUM kas_keliling_schedules_2026.sql
-- =====================================================

-- Hapus data lama jika ada (opsional, hati-hati di production!)
-- TRUNCATE TABLE kas_keliling_schedules;
-- TRUNCATE TABLE kas_keliling;

-- =====================================================
-- INSERT DATA MASTER KAS KELILING
-- =====================================================

-- Area 1: Pasar Pagi (Senin & Kamis)
INSERT INTO kas_keliling (id, area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active, created_at, updated_at) VALUES
(1, 
 'Pasar Pagi', 
 '["Senin", "Kamis"]', 
 '["Jl. Pasar Pagi", "Jl. Raya Utara", "Jl. Sudirman"]', 
 'Budi Santoso', 
 '081234567890', 
 '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening", "Penarikan Tunai"]', 
 '{"start":"08:00","end":"12:00"}', 
 1, 
 NOW(), 
 NOW());

-- Area 2: Kelurahan Sungailiat (Selasa & Jumat)
INSERT INTO kas_keliling (id, area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active, created_at, updated_at) VALUES
(2, 
 'Kelurahan Sungailiat', 
 '["Selasa", "Jumat"]', 
 '["Jl. Sungailiat", "Jl. Pemuda", "Jl. Merdeka"]', 
 'Siti Aminah', 
 '081234567891', 
 '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai", "Transfer"]', 
 '{"start":"09:00","end":"13:00"}', 
 1, 
 NOW(), 
 NOW());

-- Area 3: Pasar Belinyu (Rabu)
INSERT INTO kas_keliling (id, area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active, created_at, updated_at) VALUES
(3, 
 'Pasar Belinyu', 
 '["Rabu"]', 
 '["Jl. Belinyu", "Jl. Pasar Belinyu", "Jl. Raya Belinyu"]', 
 'Ahmad Yani', 
 '081234567892', 
 '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 
 '{"start":"08:30","end":"12:30"}', 
 1, 
 NOW(), 
 NOW());

-- Area 4: Kelurahan Pemali (Kamis)
INSERT INTO kas_keliling (id, area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active, created_at, updated_at) VALUES
(4, 
 'Kelurahan Pemali', 
 '["Kamis"]', 
 '["Jl. Pemali", "Jl. Raya Pemali"]', 
 'Dewi Lestari', 
 '081234567893', 
 '["Setoran Tabungan", "Pembayaran Angsuran"]', 
 '{"start":"09:00","end":"12:00"}', 
 1, 
 NOW(), 
 NOW());

-- Area 5: Pasar Koba (Jumat)
INSERT INTO kas_keliling (id, area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active, created_at, updated_at) VALUES
(5, 
 'Pasar Koba', 
 '["Jumat"]', 
 '["Jl. Koba", "Jl. Pasar Koba"]', 
 'Eko Prasetyo', 
 '081234567894', 
 '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 
 '{"start":"08:00","end":"12:00"}', 
 1, 
 NOW(), 
 NOW());

-- =====================================================
-- CATATAN PENTING:
-- =====================================================
-- 1. Sesuaikan ID kas_keliling dengan kebutuhan Anda
-- 2. Sesuaikan nama area, contact person, dan nomor telepon
-- 3. Sesuaikan jadwal hari, rute, layanan, dan jam operasional
-- 4. Setelah insert data master ini, jalankan kas_keliling_schedules_2026.sql
-- 5. Pastikan ID di kas_keliling_schedules_2026.sql sesuai dengan ID di sini
