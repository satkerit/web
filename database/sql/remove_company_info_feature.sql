-- SQL untuk menghapus fitur company-info lama
-- Backup data terlebih dahulu sebelum menjalankan script ini

-- 1. Backup existing data
CREATE TABLE company_infos_backup AS SELECT * FROM company_infos;

-- 2. Drop existing table (akan dibuat ulang dengan struktur baru)
DROP TABLE IF EXISTS company_infos;

-- 3. Remove menu entry (akan dibuat ulang)
DELETE FROM admin_menus WHERE `key` = 'company-info';

-- 4. Remove menu permissions (akan dibuat ulang)
DELETE FROM admin_menu_permissions WHERE admin_menu_id IN (
    SELECT id FROM admin_menus WHERE `key` = 'company-info'
);

-- Note: File-file PHP akan dihapus dan dibuat ulang melalui script