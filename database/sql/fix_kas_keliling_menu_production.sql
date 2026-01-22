-- ============================================
-- SQL Fix untuk Menu Kas Keliling di Production
-- Tanggal: 2026-01-22
-- Deskripsi: Script untuk memastikan menu kas keliling tersedia dan berfungsi
-- ============================================

-- BACKUP DULU SEBELUM MENJALANKAN!
-- mysqldump -u root -p database_name admin_menus admin_menu_permissions > backup_menus_$(date +%Y%m%d).sql

-- ============================================
-- STEP 1: CEK STATUS AWAL
-- ============================================
SELECT '=== STEP 1: CHECKING CURRENT STATUS ===' as step;

SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'Menu EXISTS' 
        ELSE 'Menu NOT FOUND - Will be created' 
    END as menu_status
FROM admin_menus 
WHERE `key` = 'kas-keliling';

-- ============================================
-- STEP 2: INSERT MENU JIKA BELUM ADA
-- ============================================
SELECT '=== STEP 2: ENSURING MENU EXISTS ===' as step;

INSERT INTO admin_menus (
    `key`, 
    name, 
    route, 
    icon,
    section, 
    `order`, 
    is_active, 
    created_at, 
    updated_at
)
SELECT 
    'kas-keliling', 
    'Kas Keliling', 
    'admin.kas-keliling.index',
    NULL,
    'Perusahaan', 
    23, 
    1, 
    NOW(), 
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM admin_menus WHERE `key` = 'kas-keliling'
);

-- Tampilkan hasil
SELECT 
    CASE 
        WHEN ROW_COUNT() > 0 THEN '✅ Menu created successfully' 
        ELSE '✅ Menu already exists' 
    END as result;

-- ============================================
-- STEP 3: GET MENU ID
-- ============================================
SELECT '=== STEP 3: GETTING MENU ID ===' as step;

SET @menu_id = (SELECT id FROM admin_menus WHERE `key` = 'kas-keliling');

SELECT 
    @menu_id as menu_id,
    CASE 
        WHEN @menu_id IS NOT NULL THEN '✅ Menu ID found' 
        ELSE '❌ Menu ID not found - ERROR' 
    END as status;

-- ============================================
-- STEP 4: INSERT PERMISSIONS UNTUK SUPER_ADMIN
-- ============================================
SELECT '=== STEP 4: ENSURING SUPER_ADMIN PERMISSION ===' as step;

INSERT INTO admin_menu_permissions (
    admin_menu_id, 
    role, 
    can_access, 
    created_at, 
    updated_at
)
SELECT 
    @menu_id,
    'super_admin',
    1,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 
    FROM admin_menu_permissions 
    WHERE admin_menu_id = @menu_id 
    AND role = 'super_admin'
);

SELECT 
    CASE 
        WHEN ROW_COUNT() > 0 THEN '✅ super_admin permission created' 
        ELSE '✅ super_admin permission already exists' 
    END as result;

-- ============================================
-- STEP 5: INSERT PERMISSIONS UNTUK ADMIN
-- ============================================
SELECT '=== STEP 5: ENSURING ADMIN PERMISSION ===' as step;

INSERT INTO admin_menu_permissions (
    admin_menu_id, 
    role, 
    can_access, 
    created_at, 
    updated_at
)
SELECT 
    @menu_id,
    'admin',
    1,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 
    FROM admin_menu_permissions 
    WHERE admin_menu_id = @menu_id 
    AND role = 'admin'
);

SELECT 
    CASE 
        WHEN ROW_COUNT() > 0 THEN '✅ admin permission created' 
        ELSE '✅ admin permission already exists' 
    END as result;

-- ============================================
-- STEP 6: INSERT PERMISSIONS UNTUK EDITOR
-- ============================================
SELECT '=== STEP 6: ENSURING EDITOR PERMISSION ===' as step;

INSERT INTO admin_menu_permissions (
    admin_menu_id, 
    role, 
    can_access, 
    created_at, 
    updated_at
)
SELECT 
    @menu_id,
    'editor',
    1,
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 
    FROM admin_menu_permissions 
    WHERE admin_menu_id = @menu_id 
    AND role = 'editor'
);

SELECT 
    CASE 
        WHEN ROW_COUNT() > 0 THEN '✅ editor permission created' 
        ELSE '✅ editor permission already exists' 
    END as result;

-- ============================================
-- STEP 7: UPDATE MENU JIKA TIDAK AKTIF
-- ============================================
SELECT '=== STEP 7: ENSURING MENU IS ACTIVE ===' as step;

UPDATE admin_menus 
SET is_active = 1,
    updated_at = NOW()
WHERE `key` = 'kas-keliling' 
AND is_active = 0;

SELECT 
    CASE 
        WHEN ROW_COUNT() > 0 THEN '✅ Menu activated' 
        ELSE '✅ Menu already active' 
    END as result;

-- ============================================
-- STEP 8: CLEAR CACHE
-- ============================================
SELECT '=== STEP 8: CLEARING CACHE ===' as step;

DELETE FROM cache WHERE `key` LIKE '%admin_menu%';

SELECT 
    CONCAT('✅ Cleared ', ROW_COUNT(), ' cache entries') as result;

-- ============================================
-- STEP 9: VERIFICATION
-- ============================================
SELECT '=== STEP 9: FINAL VERIFICATION ===' as step;

-- Verify menu
SELECT 
    'Menu Verification' as check_type,
    id,
    `key`,
    name,
    route,
    section,
    `order`,
    CASE 
        WHEN is_active = 1 THEN '✅ Active' 
        ELSE '❌ Inactive' 
    END as status
FROM admin_menus 
WHERE `key` = 'kas-keliling';

-- Verify permissions
SELECT 
    'Permissions Verification' as check_type,
    amp.role,
    CASE 
        WHEN amp.can_access = 1 THEN '✅ CAN ACCESS' 
        ELSE '❌ NO ACCESS' 
    END as access_status
FROM admin_menu_permissions amp
JOIN admin_menus am ON amp.admin_menu_id = am.id
WHERE am.`key` = 'kas-keliling'
ORDER BY amp.role;

-- Summary
SELECT 
    '=== SUMMARY ===' as title,
    (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') as menu_exists,
    (SELECT is_active FROM admin_menus WHERE `key` = 'kas-keliling') as is_active,
    (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') as permissions_count,
    CASE 
        WHEN (SELECT COUNT(*) FROM admin_menus WHERE `key` = 'kas-keliling') > 0 
         AND (SELECT is_active FROM admin_menus WHERE `key` = 'kas-keliling') = 1
         AND (SELECT COUNT(*) FROM admin_menu_permissions amp JOIN admin_menus am ON amp.admin_menu_id = am.id WHERE am.`key` = 'kas-keliling') >= 3
        THEN '✅✅✅ ALL GOOD - Menu Ready to Use!'
        ELSE '❌ ISSUES FOUND - Please check above'
    END as overall_status;

-- ============================================
-- STEP 10: NEXT STEPS
-- ============================================
SELECT '=== NEXT STEPS ===' as step;

SELECT 
    '1. Refresh browser (Ctrl+F5 or Cmd+Shift+R)' as instruction
UNION ALL
SELECT '2. Clear browser cache if needed'
UNION ALL
SELECT '3. Logout and login again'
UNION ALL
SELECT '4. Check if menu appears in admin panel under "Perusahaan" section'
UNION ALL
SELECT '5. If still not visible, run: php artisan cache:clear'
UNION ALL
SELECT '6. If still not visible, run: php artisan menu:fix-kas-keliling';

-- ============================================
-- NOTES:
-- ============================================
-- Cara menjalankan script ini:
-- 
-- Via MySQL CLI:
-- mysql -u username -p database_name < database/sql/fix_kas_keliling_menu_production.sql
--
-- Via phpMyAdmin/Adminer:
-- 1. Login ke phpMyAdmin/Adminer
-- 2. Pilih database
-- 3. Buka tab SQL
-- 4. Copy-paste isi file ini
-- 5. Klik Execute/Go
--
-- Via Tinker:
-- php artisan tinker
-- DB::unprepared(file_get_contents('database/sql/fix_kas_keliling_menu_production.sql'));
-- ============================================
