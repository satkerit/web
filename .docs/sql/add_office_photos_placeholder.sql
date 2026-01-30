-- SQL Script untuk menambahkan foto contoh ke kantor
-- Jalankan script ini jika Anda ingin menambahkan foto placeholder untuk testing

-- Update foto untuk Kantor Pusat
UPDATE offices
SET
    photo = 'offices/kantor-pusat-placeholder.jpg'
WHERE
    name = 'Kantor Pusat & Operasional';

-- Update foto untuk beberapa cabang
UPDATE offices
SET
    photo = 'offices/kantor-cabang-placeholder.jpg'
WHERE
    type = 'cabang'
    AND name IN (
        'Cabang Sungailiat',
        'Cabang Mentok',
        'Cabang Koba'
    );

-- Update foto untuk kantor kas
UPDATE offices
SET
    photo = 'offices/kantor-kas-placeholder.jpg'
WHERE
    type = 'kas'
    AND name IN ('Kas BTC', 'Kas A. Yani');

-- Catatan:
-- 1. Pastikan file foto placeholder sudah ada di storage/app/public/offices/
-- 2. Atau upload foto asli melalui Admin Panel
-- 3. Script ini hanya untuk testing/demonstrasi
