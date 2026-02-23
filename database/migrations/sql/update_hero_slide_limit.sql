-- SQL untuk update hero slide limit secara langsung
-- Jalankan query ini di database untuk mengubah jumlah maksimal slide hero

-- Update jumlah slide hero menjadi 8
UPDATE `site_settings` SET `hero_slide_limit` = 8 WHERE `id` = 1;

-- Update jumlah slide hero menjadi 3
UPDATE `site_settings` SET `hero_slide_limit` = 3 WHERE `id` = 1;

-- Update jumlah slide hero menjadi 10
UPDATE `site_settings` SET `hero_slide_limit` = 10 WHERE `id` = 1;

-- Cek nilai saat ini
SELECT `hero_slide_limit` FROM `site_settings` WHERE `id` = 1;

-- Reset ke default (5)
UPDATE `site_settings` SET `hero_slide_limit` = 5 WHERE `id` = 1;

-- Catatan: Setelah mengubah nilai, clear cache dengan perintah:
-- php artisan cache:clear-frontend