-- =====================================================
-- FIX AUCTION COLUMNS - DATA MIGRATION SCRIPT
-- =====================================================
-- File: database/sql/fix_auction_columns.sql
-- Description: Fix existing auction data for column changes
-- Created: 2026-01-26
-- Version: 1.0
-- =====================================================

-- =====================================================
-- 1. UPDATE EXISTING AUCTION DATA
-- =====================================================

-- Update auction_number for existing records without it
UPDATE `auctions` 
SET `auction_number` = CONCAT('LEL/', YEAR(CURDATE()), '/', LPAD(id, 4, '0'))
WHERE `auction_number` IS NULL OR `auction_number` = '';

-- Update object_number for existing records without it
UPDATE `auctions` 
SET `object_number` = CONCAT('OBJ-', YEAR(CURDATE()), '-', LPAD(id, 3, '0'))
WHERE `object_number` IS NULL OR `object_number` = '';

-- Update auction_location for existing records without it
UPDATE `auctions` 
SET `auction_location` = COALESCE(`city`, 'Kantor Lelang')
WHERE `auction_location` IS NULL OR `auction_location` = '';

-- Update contact_person for existing records without it
UPDATE `auctions` 
SET `contact_person` = 'Admin Lelang'
WHERE `contact_person` IS NULL OR `contact_person` = '';

-- Update contact_phone for existing records without it
UPDATE `auctions` 
SET `contact_phone` = '0717-123456'
WHERE `contact_phone` IS NULL OR `contact_phone` = '';

-- Update address from location if address is empty
UPDATE `auctions` 
SET `address` = COALESCE(`city`, 'Alamat tidak tersedia')
WHERE `address` IS NULL OR `address` = '';

-- =====================================================
-- 2. UPDATE STATUS VALUES
-- =====================================================

-- Update old status values to new ones
UPDATE `auctions` SET `status` = 'published' WHERE `status` = 'upcoming';
UPDATE `auctions` SET `status` = 'auction_ongoing' WHERE `status` = 'ongoing';
UPDATE `auctions` SET `status` = 'auction_completed' WHERE `status` = 'closed';

-- =====================================================
-- 3. UPDATE AUCTION_TYPE VALUES
-- =====================================================

-- Update old auction_type values to new ones
UPDATE `auctions` SET `auction_type` = 'eksekusi_hak_tanggungan' WHERE `auction_type` = 'eksekusi';
UPDATE `auctions` SET `auction_type` = 'non_eksekusi_sukarela' WHERE `auction_type` = 'sukarela';

-- =====================================================
-- 4. SET DEFAULT VALUES FOR NEW COLUMNS
-- =====================================================

-- Set default deposit percentage
UPDATE `auctions` 
SET `deposit_percentage` = 20.00 
WHERE `deposit_percentage` IS NULL;

-- Set default payment deadline days
UPDATE `auctions` 
SET `payment_deadline_days` = 30 
WHERE `payment_deadline_days` IS NULL;

-- Set default auction method
UPDATE `auctions` 
SET `auction_method` = 'lelang_terbuka' 
WHERE `auction_method` IS NULL OR `auction_method` = '';

-- Set default province
UPDATE `auctions` 
SET `province` = 'Kepulauan Bangka Belitung' 
WHERE `province` IS NULL OR `province` = '';

-- =====================================================
-- 5. CALCULATE DEPOSIT AMOUNT FROM PERCENTAGE
-- =====================================================

-- Calculate deposit_amount from limit_price and deposit_percentage if not set
UPDATE `auctions` 
SET `deposit_amount` = (`limit_price` * `deposit_percentage` / 100)
WHERE `deposit_amount` IS NULL 
AND `limit_price` IS NOT NULL 
AND `deposit_percentage` IS NOT NULL;

-- =====================================================
-- 6. SET PUBLISHED_AT FOR PUBLISHED AUCTIONS
-- =====================================================

-- Set published_at for auctions that are published but don't have published_at
UPDATE `auctions` 
SET `published_at` = `created_at`
WHERE `status` IN ('published', 'registration_open', 'auction_scheduled', 'auction_ongoing', 'sold')
AND `published_at` IS NULL;

-- =====================================================
-- 7. VERIFY DATA INTEGRITY
-- =====================================================

-- Show auctions with missing required fields
SELECT 'Auctions with missing required fields:' as info;
SELECT id, title, 
       CASE WHEN auction_number IS NULL OR auction_number = '' THEN 'Missing auction_number' ELSE 'OK' END as auction_number_status,
       CASE WHEN address IS NULL OR address = '' THEN 'Missing address' ELSE 'OK' END as address_status,
       CASE WHEN limit_price IS NULL THEN 'Missing limit_price' ELSE 'OK' END as limit_price_status,
       CASE WHEN auction_location IS NULL OR auction_location = '' THEN 'Missing auction_location' ELSE 'OK' END as auction_location_status,
       CASE WHEN contact_person IS NULL OR contact_person = '' THEN 'Missing contact_person' ELSE 'OK' END as contact_person_status,
       CASE WHEN contact_phone IS NULL OR contact_phone = '' THEN 'Missing contact_phone' ELSE 'OK' END as contact_phone_status
FROM auctions 
WHERE (auction_number IS NULL OR auction_number = '')
   OR (address IS NULL OR address = '')
   OR (limit_price IS NULL)
   OR (auction_location IS NULL OR auction_location = '')
   OR (contact_person IS NULL OR contact_person = '')
   OR (contact_phone IS NULL OR contact_phone = '')
LIMIT 10;

-- Show status distribution
SELECT 'Status distribution:' as info;
SELECT status, COUNT(*) as count 
FROM auctions 
GROUP BY status 
ORDER BY count DESC;

-- Show auction_type distribution
SELECT 'Auction type distribution:' as info;
SELECT auction_type, COUNT(*) as count 
FROM auctions 
GROUP BY auction_type 
ORDER BY count DESC;

-- =====================================================
-- 8. CLEAN UP ORPHANED DATA (Optional)
-- =====================================================

-- Remove auctions with invalid asset_type (if any)
-- DELETE FROM auctions WHERE asset_type NOT IN ('tanah','rumah','ruko','apartemen','gedung','pabrik','kendaraan','mesin','lainnya');

-- Remove auctions with invalid status (if any)
-- DELETE FROM auctions WHERE status NOT IN ('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed');

-- =====================================================
-- END OF FIX SCRIPT
-- =====================================================

SELECT 'Auction columns fix script executed successfully!' as status,
       (SELECT COUNT(*) FROM auctions) as total_auctions,
       (SELECT COUNT(*) FROM auctions WHERE status = 'published') as published_auctions,
       (SELECT COUNT(*) FROM auctions WHERE auction_number IS NOT NULL) as auctions_with_number;