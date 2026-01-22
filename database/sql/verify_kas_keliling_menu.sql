-- ============================================
-- SQL Verification untuk Menu Kas Keliling
-- Tanggal: 2026-01-22
-- Deskripsi: Query untuk memastikan menu kas keliling tersedia di production
-- ============================================

-- ============================================
-- 1. CEK APAKAH MENU KAS KELILING ADA
-- ============================================
SELECT 
    'Menu Kas Keliling' as check_item,
    CASE 
        WHEN COUNT(*) > 0 THEN '✅ EXISTS' 
        ELSE '❌ NOT FOUND' 
    END as status,
    COUNT(*) as count
FROM admin_menus 
WHERE `key` = 'kas-keliling';

-- ============================================
-- 2. DETAIL MENU KAS KELILING
-- ============================================
SELECT 
    id,
    `key`,
    name,
    route,
    section,
    `order`,
    is_active,
    created_at,
    updated_at
FROM admin_menus 
WHERE `key` = 'kas-keliling';

-- ============================================
-- 3. CEK PERMISSIONS UNTUK MENU KAS KELILING
-- ============================================
SELECT 
    'Permissions' as check_item,
    CASE 
        WHEN COUNT(*) >= 3 THEN '✅ OK (3+ roles)' 
        WHEN COUNT(*) > 0 THEN '⚠️ INCOMPLETE (< 3 roles)'
        ELSE '❌ NO PERMISSIONS' 
    END as status,
    COUNT(*) as total_permissions,
    GROUP_CONCAT(role ORDER BY role SEPARATOR ', ') as roles
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.`key` = 'kas-keliling';

-- ============================================
-- 4. DETAIL PERMISSIONS PER ROLE
-- ============================================
SELECT 
    am.name as menu_name,
    amp.role,
    CASE 
        WHEN amp.can_access = 1 THEN '✅ CAN ACCESS' 
        ELSE '❌ NO ACCESS' 
    END as access_status,
    amp.created_at,
    amp.updated_at
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.`key` = 'kas-keliling'
ORDER BY amp.role;

-- ============================================
-- 5. CEK SEMUA MENU DI SECTION PERUSAHAAN
-- ============================================
SELECT 
    `key`,
    name,
    route,
    `order`,
    CASE 
        WHEN is_active = 1 THEN '✅ Active' 
        ELSE '❌ Inactive' 
    END as status
FROM admin_menus 
WHERE section = 'Perusahaan'
ORDER BY `order`;

-- ============================================
-- 6. CEK CACHE YANG MUNGKIN MENGGANGGU
-- ============================================
SELECT 
    'Cache Entries' as check_item,
    COUNT(*) as total_cache_entries
FROM cache 
WHERE `key` LIKE '%admin_menu%';

-- Jika ada cache, tampilkan detail
SELECT 
    `key`,
    LEFT(value, 100) as value_preview,
    expiration
FROM cache 
WHERE `key` LIKE '%admin_menu%'
LIMIT 10;

-- ============================================
-- 7. SUMMARY CHECK
-- ============================================
SELECT 
    'SUMMARY CHECK' as title,
    (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') as menu_exists,
    (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') as permissions_count,
    (SELECT is_active FROM admin_menus WHERE `key` = 'kas-keliling') as is_active,
    CASE 
        WHEN (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') > 0 
         AND (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') >= 3
         AND (SELECT is_active FROM admin_menus WHERE `key` = 'kas-keliling') = 1
        THEN '✅ ALL GOOD - Menu Ready'
        ELSE '❌ ISSUES FOUND - Run Fix Script'
    END as overall_status;

-- ============================================
-- 8. JIKA MENU TIDAK ADA, INSERT MENU
-- ============================================
-- UNCOMMENT JIKA MENU TIDAK ADA

-- INSERT INTO admin_menus (
--     `key`, 
--     name, 
--     route, 
--     section, 
--     `order`, 
--     is_active, 
--     created_at, 
--     updated_at
-- ) VALUES (
--     'kas-keliling', 
--     'Kas Keliling', 
--     'admin.kas-keliling.index', 
--     'Perusahaan', 
--     23, 
--     1, 
--     NOW(), 
--     NOW()
-- );

-- ============================================
-- 9. JIKA PERMISSIONS TIDAK ADA, INSERT PERMISSIONS
-- ============================================
-- UNCOMMENT JIKA PERMISSIONS TIDAK ADA
-- GANTI @menu_id dengan ID menu yang sebenarnya

-- SET @menu_id = (SELECT id FROM admin_menus WHERE `key` = 'kas-keliling');

-- INSERT INTO admin_menu_permissions (admin_menu_id, role, can_access, created_at, updated_at)
-- VALUES 
--     (@menu_id, 'super_admin', 1, NOW(), NOW()),
--     (@menu_id, 'admin', 1, NOW(), NOW()),
--     (@menu_id, 'editor', 1, NOW(), NOW());

-- ============================================
-- 10. CLEAR CACHE (JIKA DIPERLUKAN)
-- ============================================
-- UNCOMMENT UNTUK CLEAR CACHE

-- DELETE FROM cache WHERE `key` LIKE '%admin_menu%';

-- ============================================
-- NOTES:
-- ============================================
-- Setelah menjalankan query ini:
-- 1. Jika status "ALL GOOD" - Menu sudah siap
-- 2. Jika status "ISSUES FOUND" - Jalankan fix script:
--    php artisan menu:fix-kas-keliling
-- 3. Jika menu tidak ada - Uncomment section 8 dan 9
-- 4. Jika ada cache - Uncomment section 10 atau jalankan:
--    php artisan cache:clear
-- ============================================
