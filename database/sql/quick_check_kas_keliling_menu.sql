-- ============================================
-- Quick Check: Menu Kas Keliling
-- Tanggal: 2026-01-22
-- Deskripsi: Query cepat untuk cek status menu kas keliling
-- ============================================

-- QUICK CHECK - Copy paste query ini ke phpMyAdmin/Adminer

-- 1. Cek menu ada atau tidak
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN '✅ MENU EXISTS' 
        ELSE '❌ MENU NOT FOUND' 
    END as status,
    COUNT(*) as count
FROM admin_menus 
WHERE `key` = 'kas-keliling';

-- 2. Cek detail menu
SELECT * FROM admin_menus WHERE `key` = 'kas-keliling';

-- 3. Cek permissions
SELECT 
    amp.role,
    CASE WHEN amp.can_access = 1 THEN '✅ YES' ELSE '❌ NO' END as can_access
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.`key` = 'kas-keliling';

-- 4. Summary
SELECT 
    (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') as menu_exists,
    (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') as permissions_count,
    CASE 
        WHEN (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') > 0 
         AND (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') >= 3
        THEN '✅ OK'
        ELSE '❌ PROBLEM'
    END as status;
