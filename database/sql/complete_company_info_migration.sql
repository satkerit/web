-- Complete Company Info Migration Script
-- This script will migrate from old company-info structure to new Laravel 12 approach
-- Run this script in sequence

-- =====================================================
-- STEP 1: Backup existing data
-- =====================================================
CREATE TABLE IF NOT EXISTS company_infos_backup AS SELECT * FROM company_infos;

-- =====================================================
-- STEP 2: Drop old table and recreate with new structure
-- =====================================================
DROP TABLE IF EXISTS company_infos;

-- The new table will be created by running: php artisan migrate
-- Make sure to run the migration: 2026_02_01_230000_create_new_company_infos_table.php

-- =====================================================
-- STEP 3: Migrate data from backup (run after migration)
-- =====================================================
-- This will be handled by migrate_company_info_data.sql

-- =====================================================
-- STEP 4: Update admin menu
-- =====================================================
-- Remove old menu entry
DELETE FROM admin_menu_permissions WHERE admin_menu_id IN (
    SELECT id FROM admin_menus WHERE `key` = 'company-info'
);
DELETE FROM admin_menus WHERE `key` = 'company-info';

-- New menu entry will be created by AdminMenuSeeder

-- =====================================================
-- STEP 5: Clear application cache
-- =====================================================
-- Run these artisan commands after SQL execution:
-- php artisan cache:clear
-- php artisan config:clear
-- php artisan view:clear
-- php artisan route:clear

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================
SELECT 'Migration Status Check' as info;

-- Check if backup exists
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'Backup table exists'
        ELSE 'Backup table missing'
    END as backup_status
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
AND table_name = 'company_infos_backup';

-- Check new table structure
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'New table structure ready'
        ELSE 'New table not found - run migration first'
    END as table_status
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
AND table_name = 'company_infos';

-- Check menu status
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'Menu entry exists'
        ELSE 'Menu entry missing - run seeder'
    END as menu_status
FROM admin_menus 
WHERE `key` = 'company-info';

SELECT 'Migration script completed' as final_status;