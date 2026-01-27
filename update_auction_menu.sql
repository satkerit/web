-- =====================================================
-- SQL Script untuk Update Menu Lelang menjadi Lelang Agunan
-- =====================================================
-- Tanggal: 27 Januari 2026
-- Deskripsi: Mengubah nama menu dari "Lelang" menjadi "Lelang Agunan"
-- =====================================================

-- 1. Update nama menu di tabel admin_menus
UPDATE admin_menus 
SET name = 'Lelang Agunan'
WHERE key = 'auctions' AND name = 'Lelang';

-- 2. Verifikasi perubahan (Query untuk memeriksa hasil update)
-- SELECT id, key, name, route, section, `order` 
-- FROM admin_menus 
-- WHERE key = 'auctions';

-- =====================================================
-- Alternatif: Jika menu belum ada, gunakan INSERT
-- =====================================================

-- Cek apakah menu auctions sudah ada
-- Jika belum ada, jalankan INSERT berikut:

INSERT IGNORE INTO admin_menus (key, name, route, section, `order`, is_active, created_at, updated_at)
VALUES ('auctions', 'Lelang Agunan', 'admin.auctions.index', 'Konten', 13, 1, NOW(), NOW());

-- =====================================================
-- Update permissions untuk menu auctions (jika diperlukan)
-- =====================================================

-- Pastikan semua role yang seharusnya memiliki akses ke menu auctions memiliki permission
-- Dapatkan ID menu auctions terlebih dahulu
SET @menu_id = (SELECT id FROM admin_menus WHERE key = 'auctions' LIMIT 1);

-- Insert permissions untuk super_admin jika belum ada
INSERT IGNORE INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'super_admin', 1, NOW(), NOW());

-- Insert permissions untuk admin jika belum ada
INSERT IGNORE INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'admin', 1, NOW(), NOW());

-- Insert permissions untuk editor jika belum ada
INSERT IGNORE INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
VALUES (@menu_id, 'editor', 1, NOW(), NOW());

-- =====================================================
-- Query untuk verifikasi hasil akhir
-- =====================================================

-- Tampilkan menu auctions dan permissions-nya
SELECT 
    am.id,
    am.key,
    am.name,
    am.route,
    am.section,
    am.order,
    am.is_active,
    amp.role,
    amp.can_access
FROM admin_menus am
LEFT JOIN admin_menu_permissions amp ON am.id = amp.admin_menu_id
WHERE am.key = 'auctions'
ORDER BY amp.role;

-- =====================================================
-- Rollback Script (jika diperlukan)
-- =====================================================

-- Untuk mengembalikan nama menu ke "Lelang":
-- UPDATE admin_menus 
-- SET name = 'Lelang'
-- WHERE key = 'auctions' AND name = 'Lelang Agunan';

-- =====================================================
-- Catatan Tambahan
-- =====================================================

-- 1. Script ini menggunakan IGNORE pada INSERT untuk menghindari error
--    jika data sudah ada
-- 2. Menggunakan variabel @menu_id untuk mendapatkan ID menu secara dinamis
-- 3. Script ini aman dijalankan berulang kali
-- 4. Pastikan untuk backup database sebelum menjalankan script ini
-- 5. Setelah menjalankan script, clear cache aplikasi jika diperlukan:
--    php artisan cache:clear
--    php artisan config:clear
--    php artisan route:clear
--    php artisan view:clear

-- =====================================================
-- Perintah Laravel Artisan (alternatif)
-- =====================================================

-- Sebagai alternatif, Anda juga bisa menjalankan seeder:
-- php artisan db:seed --class=AdminMenuSeeder

-- Atau membuat migration baru:
-- php artisan make:migration update_auction_menu_name