-- ============================================
-- SQL Complete Structure untuk Kas Keliling
-- Tanggal: 2026-01-22
-- Deskripsi: Struktur lengkap tabel kas_keliling dan kas_keliling_schedules
-- ============================================

-- ============================================
-- 1. Tabel: kas_keliling
-- ============================================

CREATE TABLE IF NOT EXISTS `kas_keliling` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama area kas keliling',
  `schedule_date` date DEFAULT NULL COMMENT 'DEPRECATED - Gunakan kas_keliling_schedules',
  `day_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DEPRECATED - Gunakan kas_keliling_schedules',
  `schedule` json DEFAULT NULL COMMENT 'DEPRECATED - Gunakan kas_keliling_schedules',
  `route` json DEFAULT NULL COMMENT 'DEPRECATED - Gunakan kas_keliling_schedules',
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama petugas/contact person',
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor telepon contact',
  `services_offered` json DEFAULT NULL COMMENT 'Layanan yang ditawarkan (array)',
  `operational_hours` json DEFAULT NULL COMMENT 'Jam operasional (array)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel utama kas keliling';

-- ============================================
-- 2. Tabel: kas_keliling_schedules
-- ============================================

CREATE TABLE IF NOT EXISTS `kas_keliling_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kas_keliling_id` bigint unsigned NOT NULL COMMENT 'Foreign key ke kas_keliling',
  `schedule_date` date NOT NULL COMMENT 'Tanggal jadwal',
  `day_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama hari (auto-generated)',
  `start_time` time DEFAULT NULL COMMENT 'Jam mulai',
  `end_time` time DEFAULT NULL COMMENT 'Jam selesai',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi spesifik',
  `route` json DEFAULT NULL COMMENT 'Rute dalam format JSON',
  `services_offered` json DEFAULT NULL COMMENT 'Layanan yang ditawarkan',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan tambahan',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kas_keliling_schedules_kas_keliling_id_schedule_date_index` (`kas_keliling_id`,`schedule_date`),
  CONSTRAINT `kas_keliling_schedules_kas_keliling_id_foreign` FOREIGN KEY (`kas_keliling_id`) REFERENCES `kas_keliling` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel jadwal kas keliling';

-- ============================================
-- 3. Sample Data (Optional)
-- ============================================

-- Contoh data kas keliling
INSERT INTO `kas_keliling` (`area_name`, `contact_person`, `contact_phone`, `services_offered`, `is_active`) VALUES
('Pasar Pagi Sungailiat', 'Budi Santoso', '0812-3456-7890', '["Setoran Tabungan", "Pembayaran Angsuran", "Penarikan Tunai"]', 1),
('Kelurahan Pemali', 'Siti Aminah', '0813-9876-5432', '["Setoran Tabungan", "Pembayaran Angsuran"]', 1),
('Komplek Perumahan Griya', NULL, NULL, '["Setoran Tabungan"]', 1);

-- Contoh data jadwal (sesuaikan kas_keliling_id dengan data yang ada)
-- INSERT INTO `kas_keliling_schedules` (`kas_keliling_id`, `schedule_date`, `day_name`, `start_time`, `end_time`, `location`, `services_offered`, `is_active`) VALUES
-- (1, '2026-01-25', 'Sabtu', '08:00:00', '12:00:00', 'Depan Pasar Pagi', '["Setoran Tabungan", "Pembayaran Angsuran"]', 1),
-- (1, '2026-02-01', 'Sabtu', '08:00:00', '12:00:00', 'Depan Pasar Pagi', '["Setoran Tabungan", "Pembayaran Angsuran"]', 1),
-- (2, '2026-01-26', 'Minggu', '09:00:00', '13:00:00', 'Kantor Kelurahan Pemali', '["Setoran Tabungan"]', 1);

-- ============================================
-- 4. Verifikasi
-- ============================================

-- Cek struktur tabel kas_keliling
DESCRIBE `kas_keliling`;

-- Cek struktur tabel kas_keliling_schedules
DESCRIBE `kas_keliling_schedules`;

-- Cek data kas_keliling
SELECT * FROM `kas_keliling`;

-- Cek data kas_keliling_schedules
SELECT * FROM `kas_keliling_schedules`;

-- Cek relasi dengan join
SELECT 
    kk.id,
    kk.area_name,
    kk.contact_person,
    kk.contact_phone,
    COUNT(kks.id) as total_schedules,
    kk.is_active
FROM kas_keliling kk
LEFT JOIN kas_keliling_schedules kks ON kk.id = kks.kas_keliling_id
GROUP BY kk.id, kk.area_name, kk.contact_person, kk.contact_phone, kk.is_active;
