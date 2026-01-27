-- =====================================================
-- COMPREHENSIVE SQL SCRIPT - UPDATE AUCTION MENU
-- =====================================================
-- Tanggal: 27 Januari 2026
-- Tujuan: Mengubah semua referensi menu dari "Lelang" ke "Lelang Agunan"
-- Database: Laravel CMS BPRS Bangka Belitung
-- =====================================================

-- Mulai transaksi untuk memastikan atomicity
START TRANSACTION;

-- =====================================================
-- 1. UPDATE MENU UTAMA
-- =====================================================

-- Update nama menu di tabel admin_menus
UPDATE admin_menus 
SET 
    name = 'Lelang Agunan',
    updated_at = NOW()
WHERE key = 'auctions';

-- Tampilkan hasil update
SELECT 'Menu Updated:' as status, id, key, name, route, section 
FROM admin_menus 
WHERE key = 'auctions';

-- =====================================================
-- 2. PASTIKAN MENU ADA (JIKA BELUM ADA)
-- =====================================================

-- Insert menu jika belum ada (menggunakan INSERT IGNORE)
INSERT IGNORE INTO admin_menus (
    key, 
    name, 
    route, 
    icon, 
    section, 
    `order`, 
    is_active, 
    created_at, 
    updated_at
) VALUES (
    'auctions', 
    'Lelang Agunan', 
    'admin.auctions.index', 
    NULL, 
    'Konten', 
    13, 
    1, 
    NOW(), 
    NOW()
);

-- =====================================================
-- 3. UPDATE/INSERT PERMISSIONS
-- =====================================================

-- Dapatkan ID menu auctions
SET @auction_menu_id = (SELECT id FROM admin_menus WHERE key = 'auctions' LIMIT 1);

-- Hapus permissions lama jika ada (untuk clean update)
DELETE FROM admin_menu_permissions WHERE admin_menu_id = @auction_menu_id;

-- Insert permissions baru untuk semua role yang relevan
INSERT INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at) VALUES
(@auction_menu_id, 'super_admin', 1, NOW(), NOW()),
(@auction_menu_id, 'admin', 1, NOW(), NOW()),
(@auction_menu_id, 'editor', 1, NOW(), NOW());

-- =====================================================
-- 4. VERIFIKASI HASIL
-- =====================================================

-- Tampilkan menu dan permissions yang telah diupdate
SELECT 
    'VERIFICATION RESULT' as info,
    am.id as menu_id,
    am.key as menu_key,
    am.name as menu_name,
    am.route as menu_route,
    am.section as menu_section,
    am.order as menu_order,
    am.is_active as menu_active,
    amp.role as permission_role,
    amp.can_access as can_access
FROM admin_menus am
LEFT JOIN admin_menu_permissions amp ON am.id = amp.admin_menu_id
WHERE am.key = 'auctions'
ORDER BY amp.role;

-- =====================================================
-- 5. UPDATE CACHE TIMESTAMP (OPTIONAL)
-- =====================================================

-- Jika ada tabel cache atau timestamp untuk invalidasi cache
-- UPDATE cache_timestamps SET updated_at = NOW() WHERE cache_key = 'admin_menus';

-- =====================================================
-- 6. COMMIT TRANSAKSI
-- =====================================================

-- Jika semua berjalan lancar, commit perubahan
COMMIT;

-- =====================================================
-- ROLLBACK SCRIPT (JALANKAN JIKA PERLU ROLLBACK)
-- =====================================================

/*
-- Uncomment dan jalankan jika perlu rollback

START TRANSACTION;

-- Kembalikan nama menu ke "Lelang"
UPDATE admin_menus 
SET 
    name = 'Lelang',
    updated_at = NOW()
WHERE key = 'auctions' AND name = 'Lelang Agunan';

-- Verifikasi rollback
SELECT 'ROLLBACK RESULT' as info, id, key, name, route, section 
FROM admin_menus 
WHERE key = 'auctions';

COMMIT;
*/

-- =====================================================
-- PERINTAH LARAVEL SETELAH MENJALANKAN SQL
-- =====================================================

/*
Setelah menjalankan script SQL ini, jalankan perintah berikut di terminal:

1. Clear semua cache Laravel:
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear

2. Atau jalankan migration (jika menggunakan file migration):
   php artisan migrate

3. Atau jalankan seeder (alternatif):
   php artisan db:seed --class=AdminMenuSeeder

4. Restart web server jika diperlukan
*/

-- =====================================================
-- CATATAN PENTING
-- =====================================================

/*
1. Script ini menggunakan transaksi untuk memastikan data consistency
2. Menggunakan INSERT IGNORE untuk menghindari duplicate key error
3. Menghapus dan membuat ulang permissions untuk clean update
4. Script aman dijalankan berulang kali
5. Selalu backup database sebelum menjalankan script
6. Test di environment development terlebih dahulu
7. Pastikan aplikasi dalam maintenance mode saat update production
*/

-- =====================================================
-- QUERY UNTUK MONITORING
-- =====================================================

-- Query untuk memeriksa semua menu dan permissions
SELECT 
    am.key,
    am.name,
    am.route,
    am.section,
    am.order,
    COUNT(amp.id) as permission_count,
    GROUP_CONCAT(amp.role ORDER BY amp.role) as roles
FROM admin_menus am
LEFT JOIN admin_menu_permissions amp ON am.id = amp.admin_menu_id
WHERE am.section = 'Konten'
GROUP BY am.id, am.key, am.name, am.route, am.section, am.order
ORDER BY am.order;

-- =====================================================
-- END OF SCRIPT
-- =====================================================