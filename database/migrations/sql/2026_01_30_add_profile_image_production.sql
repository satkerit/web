-- SQL Script untuk menambahkan fitur Gambar Profil Perusahaan
-- Tanggal: 2026-01-30
-- Deskripsi: Menambahkan kolom profile_image ke tabel company_infos untuk mengelola gambar profil perusahaan secara dinamis

-- Tambahkan kolom profile_image ke tabel company_infos
ALTER TABLE `company_infos`
ADD COLUMN `profile_image` VARCHAR(255) NULL AFTER `organization_structure`;

-- Verifikasi perubahan
SELECT
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'company_infos'
    AND COLUMN_NAME = 'profile_image';
