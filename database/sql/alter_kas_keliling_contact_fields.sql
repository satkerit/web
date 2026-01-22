-- ============================================
-- SQL ALTER untuk Kas Keliling
-- Tanggal: 2026-01-22
-- Deskripsi: Memperbaiki field contact_person dan contact_phone menjadi nullable
-- ============================================

-- Ubah contact_person menjadi nullable
ALTER TABLE `kas_keliling` 
MODIFY COLUMN `contact_person` VARCHAR(255) NULL;

-- Ubah contact_phone menjadi nullable
ALTER TABLE `kas_keliling` 
MODIFY COLUMN `contact_phone` VARCHAR(255) NULL;

-- Verifikasi perubahan
DESCRIBE `kas_keliling`;
