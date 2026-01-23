-- =====================================================
-- COMPLETE REBUILD KAS KELILING
-- Jalankan file ini untuk rebuild lengkap
-- BACKUP DATABASE DULU!
-- =====================================================

-- Step 1: Drop old tables
DROP TABLE IF EXISTS kas_keliling_schedules;
DROP TABLE IF EXISTS kas_keliling;

-- Step 2: Create new simple structure
CREATE TABLE IF NOT EXISTS kas_keliling_schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    schedule_date DATE NOT NULL COMMENT 'Tanggal jadwal',
    day_name VARCHAR(20) NOT NULL COMMENT 'Nama hari',
    start_time TIME NOT NULL COMMENT 'Jam mulai',
    end_time TIME NOT NULL COMMENT 'Jam selesai',
    location VARCHAR(255) NOT NULL COMMENT 'Lokasi/Tujuan',
    facility TEXT NULL COMMENT 'Fasilitas (pisahkan dengan koma)',
    pic_name VARCHAR(255) NULL COMMENT 'Nama PIC',
    pic_phone VARCHAR(20) NULL COMMENT 'Nomor telepon PIC',
    notes TEXT NULL COMMENT 'Catatan tambahan',
    is_active BOOLEAN DEFAULT 1 COMMENT 'Status aktif',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_schedule_date (schedule_date),
    INDEX idx_is_active (is_active),
    INDEX idx_schedule_date_active (schedule_date, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 3: Insert sample data
INSERT INTO kas_keliling_schedules (schedule_date, day_name, start_time, end_time, location, facility, pic_name, pic_phone, notes, is_active) VALUES
('2026-01-27', 'Senin', '08:00:00', '12:00:00', 'Pasar Pagi Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai', 'Budi Santoso', '081234567890', 'Jadwal rutin', 1),
('2026-01-28', 'Selasa', '09:00:00', '13:00:00', 'Kelurahan Pemali', 'Setoran Tabungan, Pembayaran Angsuran', 'Siti Aminah', '081234567891', 'Jadwal rutin', 1),
('2026-01-29', 'Rabu', '08:30:00', '12:30:00', 'Pasar Belinyu', 'Setoran Tabungan, Pembayaran Angsuran, Pembukaan Rekening', 'Ahmad Yani', '081234567892', 'Jadwal rutin', 1),
('2026-01-30', 'Kamis', '08:00:00', '12:00:00', 'Pasar Koba', 'Setoran Tabungan, Pembayaran Angsuran', 'Dewi Lestari', '081234567893', 'Jadwal rutin', 1),
('2026-01-31', 'Jumat', '09:00:00', '12:00:00', 'Kelurahan Sungailiat', 'Setoran Tabungan, Pembayaran Angsuran, Transfer', 'Eko Prasetyo', '081234567894', 'Jadwal rutin', 1);

SELECT 'Rebuild completed successfully!' as status;
SELECT COUNT(*) as total_schedules FROM kas_keliling_schedules;
