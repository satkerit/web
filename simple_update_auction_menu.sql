-- =====================================================
-- SIMPLE UPDATE SCRIPT - AUCTION MENU NAME
-- =====================================================
-- Mengubah nama menu "Lelang" menjadi "Lelang Agunan"
-- =====================================================

-- Update nama menu
UPDATE admin_menus 
SET name = 'Lelang Agunan', updated_at = NOW()
WHERE key = 'auctions' AND name = 'Lelang';

-- Verifikasi hasil
SELECT id, key, name, route, section, `order` 
FROM admin_menus 
WHERE key = 'auctions';