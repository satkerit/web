-- SQL untuk migrasi data company-info dari struktur lama ke struktur baru
-- Jalankan setelah migration baru dijalankan

-- 1. Migrate data from backup to new table structure
INSERT INTO company_infos (
    id,
    name,
    tagline,
    description,
    established_year,
    address,
    phone,
    fax,
    whatsapp,
    email,
    email_contact,
    email_complaint,
    email_whistleblowing,
    website,
    logo,
    logo_footer,
    logo_footer_remove_bg,
    logo_footer_opacity,
    favicon,
    profile_image,
    organization_structure,
    vision,
    mission,
    history,
    stat_years_experience,
    stat_branch_offices,
    stat_total_assets,
    stat_cash_offices,
    stat_mobile_cash_offices,
    legacy_visitor_count,
    facebook,
    instagram,
    twitter,
    youtube,
    linkedin,
    tiktok,
    ojk_license,
    ojk_tagline,
    lps_tagline,
    lps_guarantee_amount,
    footer_description,
    meta_description,
    meta_keywords,
    operational_hours,
    created_at,
    updated_at
)
SELECT 
    id,
    name,
    tagline,
    description,
    established_year,
    address,
    phone,
    fax,
    whatsapp,
    email,
    email_contact,
    email_complaint,
    email_whistleblowing,
    website,
    logo,
    logo_footer,
    logo_footer_remove_bg,
    logo_footer_opacity,
    favicon,
    profile_image,
    organization_structure,
    vision,
    mission,
    history,
    stat_years_experience,
    stat_branch_offices,
    stat_total_assets,
    stat_cash_offices,
    stat_mobile_cash_offices,
    legacy_visitor_count,
    facebook,
    instagram,
    twitter,
    youtube,
    linkedin,
    tiktok,
    ojk_license,
    ojk_tagline,
    lps_tagline,
    lps_guarantee_amount,
    footer_description,
    meta_description,
    meta_keywords,
    operational_hours,
    created_at,
    updated_at
FROM company_infos_backup
WHERE id IS NOT NULL;

-- 2. Re-create admin menu entry
INSERT INTO admin_menus (
    `key`,
    name,
    route,
    section,
    `order`,
    is_active,
    created_at,
    updated_at
) VALUES (
    'company-info',
    'Profil Perusahaan',
    'admin.company-info.edit',
    'Perusahaan',
    20,
    1,
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    route = VALUES(route),
    section = VALUES(section),
    `order` = VALUES(`order`),
    updated_at = NOW();

-- 3. Re-create menu permissions for all roles
INSERT INTO admin_menu_permissions (
    admin_menu_id,
    role_id,
    can_access,
    created_at,
    updated_at
)
SELECT 
    (SELECT id FROM admin_menus WHERE `key` = 'company-info') as admin_menu_id,
    r.id as role_id,
    CASE 
        WHEN r.name IN ('super_admin', 'admin') THEN 1
        ELSE 0
    END as can_access,
    NOW() as created_at,
    NOW() as updated_at
FROM roles r
WHERE (SELECT id FROM admin_menus WHERE `key` = 'company-info') IS NOT NULL
ON DUPLICATE KEY UPDATE
    can_access = VALUES(can_access),
    updated_at = NOW();

-- 4. Clear cache (will be handled by application)
-- Cache will be cleared automatically when model is saved

-- 5. Verification queries
SELECT 'Data Migration Summary' as info;
SELECT COUNT(*) as total_records FROM company_infos;
SELECT COUNT(*) as menu_permissions FROM admin_menu_permissions 
WHERE admin_menu_id = (SELECT id FROM admin_menus WHERE `key` = 'company-info');

-- 6. Clean up backup table (optional - uncomment if you want to remove backup)
-- DROP TABLE IF EXISTS company_infos_backup;