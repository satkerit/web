-- =====================================================
-- FIX PRODUCTION AUCTION DATA
-- =====================================================
-- File ini untuk memperbaiki data auction yang menyebabkan error 500
-- Menambahkan data minimal dan memperbaiki data yang bermasalah
-- =====================================================

-- 1. Update null city values
UPDATE `auctions` 
SET `city` = 'Pangkalpinang' 
WHERE `city` IS NULL OR `city` = '';

-- 2. Update null or zero limit_price values
UPDATE `auctions` 
SET `limit_price` = 100000000 
WHERE `limit_price` IS NULL OR `limit_price` = 0;

-- 3. Update invalid status values to valid enum values
UPDATE `auctions` 
SET `status` = 'published' 
WHERE `status` NOT IN ('draft','published','registration_open','registration_closed','auction_scheduled','auction_ongoing','auction_completed','sold','unsold','cancelled','postponed');

-- 4. Set default organizer info if null
UPDATE `auctions` 
SET 
    `organizer_name` = 'BPRS Babel',
    `organizer_type` = 'Bank Pembiayaan Rakyat Syariah',
    `province` = 'Kepulauan Bangka Belitung'
WHERE `organizer_name` IS NULL;

-- 5. Generate auction_number if null
UPDATE `auctions` 
SET `auction_number` = CONCAT('LEL/', YEAR(COALESCE(created_at, NOW())), '/', LPAD(id, 3, '0'))
WHERE `auction_number` IS NULL OR `auction_number` = '';

-- 6. Generate slug if null
UPDATE `auctions` 
SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(title, ' ', '-'), '--', '-'), '---', '-'), '----', '-'))
WHERE `slug` IS NULL OR `slug` = '';

-- 7. Set published_at for published auctions
UPDATE `auctions` 
SET `published_at` = COALESCE(created_at, NOW())
WHERE `status` IN ('published', 'registration_open', 'auction_scheduled', 'sold') 
AND `published_at` IS NULL;

-- 8. Insert sample data if table is empty
INSERT IGNORE INTO `auctions` (
    `title`, `slug`, `auction_number`, `asset_type`, `address`, `city`, `province`,
    `auction_type`, `limit_price`, `status`, `contact_person`, `contact_phone`,
    `organizer_name`, `published_at`, `created_at`, `updated_at`
) 
SELECT * FROM (
    SELECT 
        'Rumah Tinggal Strategis' as title,
        'rumah-tinggal-strategis' as slug,
        'LEL/2026/001' as auction_number,
        'rumah' as asset_type,
        'Jl. Jenderal Sudirman No. 123' as address,
        'Pangkalpinang' as city,
        'Kepulauan Bangka Belitung' as province,
        'eksekusi_hak_tanggungan' as auction_type,
        750000000.00 as limit_price,
        'published' as status,
        'Ahmad Fauzi' as contact_person,
        '0717-123456' as contact_phone,
        'BPRS Babel' as organizer_name,
        NOW() as published_at,
        NOW() as created_at,
        NOW() as updated_at
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM `auctions` LIMIT 1
);

-- 9. Verify the fixes
SELECT 
    COUNT(*) as total_auctions,
    COUNT(CASE WHEN city IS NOT NULL THEN 1 END) as with_city,
    COUNT(CASE WHEN limit_price > 0 THEN 1 END) as with_price,
    COUNT(CASE WHEN status IN ('published', 'registration_open', 'auction_scheduled') THEN 1 END) as published_count
FROM `auctions`;

-- Show sample data
SELECT 
    id, title, city, limit_price, status, auction_date, organizer_name
FROM `auctions` 
ORDER BY created_at DESC 
LIMIT 3;