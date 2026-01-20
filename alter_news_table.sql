-- Query ALTER TABLE untuk menambahkan kolom SEO ke tabel news
-- Jalankan query ini jika migration belum berjalan atau ada masalah dengan migration

-- Menambahkan kolom meta_description setelah kolom excerpt
ALTER TABLE `news` ADD COLUMN `meta_description` TEXT NULL AFTER `excerpt`;

-- Menambahkan kolom tags setelah kolom meta_description  
ALTER TABLE `news` ADD COLUMN `tags` VARCHAR(255) NULL AFTER `meta_description`;

-- Verifikasi struktur tabel setelah perubahan
-- DESCRIBE `news`;

-- Query untuk melihat kolom yang sudah ada (opsional)
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'news' 
-- AND TABLE_SCHEMA = DATABASE()
-- ORDER BY ORDINAL_POSITION;