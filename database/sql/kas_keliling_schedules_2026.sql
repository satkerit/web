-- =====================================================
-- SQL INSERT JADWAL KAS KELILING 2026
-- File ini berisi jadwal lengkap untuk 1 tahun ke depan
-- =====================================================

-- Pastikan sudah ada data kas_keliling terlebih dahulu
-- Contoh: INSERT INTO kas_keliling (area_name, schedule, route, contact_person, contact_phone, services_offered, operational_hours, is_active)
-- VALUES ('Pasar Pagi', '[]', '[]', 'Budi Santoso', '081234567890', '["Setoran Tabungan","Pembayaran Angsuran"]', '{"start":"08:00","end":"12:00"}', 1);

-- =====================================================
-- JADWAL FEBRUARI 2026
-- =====================================================

-- Area: Pasar Pagi (Senin & Kamis)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(1, '2026-02-02', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(1, '2026-02-05', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(1, '2026-02-09', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(1, '2026-02-12', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(1, '2026-02-16', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(1, '2026-02-19', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(1, '2026-02-23', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(1, '2026-02-26', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW());

-- Area: Kelurahan Sungailiat (Selasa & Jumat)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(2, '2026-02-03', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(2, '2026-02-06', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(2, '2026-02-10', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(2, '2026-02-13', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(2, '2026-02-17', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(2, '2026-02-20', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(2, '2026-02-24', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(2, '2026-02-27', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW());

-- Area: Pasar Belinyu (Rabu)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(3, '2026-02-04', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(3, '2026-02-11', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(3, '2026-02-18', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(3, '2026-02-25', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW());


-- =====================================================
-- JADWAL MARET 2026
-- =====================================================

-- Area: Pasar Pagi (Senin & Kamis)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(1, '2026-03-02', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(1, '2026-03-05', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(1, '2026-03-09', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(1, '2026-03-12', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(1, '2026-03-16', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(1, '2026-03-19', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(1, '2026-03-23', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(1, '2026-03-26', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(1, '2026-03-30', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin minggu kelima', 1, NOW(), NOW());

-- Area: Kelurahan Sungailiat (Selasa & Jumat)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(2, '2026-03-03', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(2, '2026-03-06', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(2, '2026-03-10', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(2, '2026-03-13', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(2, '2026-03-17', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(2, '2026-03-20', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(2, '2026-03-24', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(2, '2026-03-27', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW()),
(2, '2026-03-31', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin minggu kelima', 1, NOW(), NOW());

-- Area: Pasar Belinyu (Rabu)
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(3, '2026-03-04', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu pertama', 1, NOW(), NOW()),
(3, '2026-03-11', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu kedua', 1, NOW(), NOW()),
(3, '2026-03-18', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu ketiga', 1, NOW(), NOW()),
(3, '2026-03-25', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin minggu keempat', 1, NOW(), NOW());

-- =====================================================
-- JADWAL APRIL - DESEMBER 2026
-- (Pola yang sama diulang untuk bulan-bulan berikutnya)
-- =====================================================

-- APRIL 2026
INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(1, '2026-04-06', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-09', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-13', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-16', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-20', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-23', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-27', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW()),
(1, '2026-04-30', 'Kamis', '08:00:00', '12:00:00', 'Pasar Pagi', '["Jl. Pasar Pagi", "Jl. Raya Utara"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Pembukaan Rekening"]', 'Jadwal rutin', 1, NOW(), NOW());

INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(2, '2026-04-07', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-10', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-14', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-17', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-21', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-24', 'Jumat', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW()),
(2, '2026-04-28', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Sungailiat', '["Jl. Sungailiat", "Jl. Pemuda"]', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 'Jadwal rutin', 1, NOW(), NOW());

INSERT INTO kas_keliling_schedules (kas_keliling_id, schedule_date, day_name, start_time, end_time, location, route, services_offered, notes, is_active, created_at, updated_at) VALUES
(3, '2026-04-01', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin', 1, NOW(), NOW()),
(3, '2026-04-08', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin', 1, NOW(), NOW()),
(3, '2026-04-15', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin', 1, NOW(), NOW()),
(3, '2026-04-22', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin', 1, NOW(), NOW()),
(3, '2026-04-29', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', '["Jl. Belinyu", "Jl. Pasar Belinyu"]', '["Setoran Tabungan", "Pembayaran Angsuran"]', 'Jadwal rutin', 1, NOW(), NOW());
