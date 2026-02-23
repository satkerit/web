-- SQL untuk memperbaiki kolom hero_slide_limit yang belum ada
-- Jalankan query ini untuk menambahkan kolom secara manual

-- Cek apakah kolom sudah ada
SELECT COUNT(*) as column_exists 
FROM information_schema.columns 
WHERE table_schema = DATABASE() 
AND table_name = 'site_settings' 
AND column_name = 'hero_slide_limit';

-- Jika kolom belum ada, tambahkan
ALTER TABLE `site_settings` 
ADD COLUMN `hero_slide_limit` INT NOT NULL DEFAULT 5 
AFTER `hero_slider_delay` 
COMMENT 'Maksimal jumlah slide hero yang ditampilkan';

-- Update nilai default untuk record yang sudah ada
UPDATE `site_settings` SET `hero_slide_limit` = 5 WHERE `hero_slide_limit` IS NULL;

-- Cek hasil
SELECT `hero_slide_limit`, `hero_slider_delay` FROM `site_settings` WHERE `id` = 1;

-- Clear cache setelah update
-- php artisan cache:clear-frontend