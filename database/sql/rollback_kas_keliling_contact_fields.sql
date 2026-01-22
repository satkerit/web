-- ============================================
-- SQL ROLLBACK untuk Kas Keliling Contact Fields
-- Tanggal: 2026-01-22
-- Deskripsi: Mengembalikan field contact_person dan contact_phone menjadi NOT NULL
-- PERINGATAN: Pastikan tidak ada data dengan contact_person atau contact_phone NULL sebelum menjalankan!
-- ============================================

-- Cek data yang memiliki contact_person atau contact_phone NULL
SELECT 
    id, 
    area_name, 
    contact_person, 
    contact_phone 
FROM `kas_keliling` 
WHERE contact_person IS NULL OR contact_phone IS NULL;

-- Jika ada data NULL, update dulu dengan nilai default
-- UPDATE `kas_keliling` 
-- SET contact_person = 'Tidak Ada' 
-- WHERE contact_person IS NULL;

-- UPDATE `kas_keliling` 
-- SET contact_phone = '-' 
-- WHERE contact_phone IS NULL;

-- Rollback: Ubah contact_person menjadi NOT NULL
-- ALTER TABLE `kas_keliling` 
-- MODIFY COLUMN `contact_person` VARCHAR(255) NOT NULL;

-- Rollback: Ubah contact_phone menjadi NOT NULL
-- ALTER TABLE `kas_keliling` 
-- MODIFY COLUMN `contact_phone` VARCHAR(255) NOT NULL;

-- Verifikasi perubahan
-- DESCRIBE `kas_keliling`;
