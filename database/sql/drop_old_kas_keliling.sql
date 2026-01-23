-- =====================================================
-- DROP OLD KAS KELILING TABLES
-- Jalankan file ini untuk menghapus struktur lama
-- BACKUP DATABASE DULU SEBELUM MENJALANKAN!
-- =====================================================

-- Drop foreign key constraints first
ALTER TABLE kas_keliling_schedules DROP FOREIGN KEY IF EXISTS kas_keliling_schedules_kas_keliling_id_foreign;

-- Drop tables
DROP TABLE IF EXISTS kas_keliling_schedules;
DROP TABLE IF EXISTS kas_keliling;

-- Verify tables dropped
SELECT 'Tables dropped successfully' as status;
