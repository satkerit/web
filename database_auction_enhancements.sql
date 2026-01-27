-- SQL untuk Enhancement Lelang Agunan
-- Menambahkan field informasi objek yang lebih lengkap
-- Tanggal: 27 Januari 2026

-- Pastikan tabel auctions sudah memiliki semua field yang diperlukan
-- Field-field ini sudah ada di migration, tapi untuk memastikan:

-- Cek apakah field certificate_type sudah ada
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'auctions' 
AND COLUMN_NAME = 'certificate_type';

-- Jika field belum ada, tambahkan (biasanya sudah ada dari migration)
-- ALTER TABLE auctions ADD COLUMN certificate_type ENUM('SHM','SHGB','SHP','AJB','PPJB','Girik','BPKB','Lainnya') NULL AFTER asset_description;
-- ALTER TABLE auctions ADD COLUMN certificate_number VARCHAR(255) NULL AFTER certificate_type;
-- ALTER TABLE auctions ADD COLUMN certificate_date DATE NULL AFTER certificate_number;
-- ALTER TABLE auctions ADD COLUMN certificate_issued_by VARCHAR(255) NULL AFTER certificate_date;

-- Cek field property details
-- ALTER TABLE auctions ADD COLUMN land_area DECIMAL(10,2) NULL AFTER certificate_issued_by;
-- ALTER TABLE auctions ADD COLUMN building_area DECIMAL(10,2) NULL AFTER land_area;
-- ALTER TABLE auctions ADD COLUMN building_condition VARCHAR(255) NULL AFTER building_area;
-- ALTER TABLE auctions ADD COLUMN floors INT NULL AFTER building_condition;
-- ALTER TABLE auctions ADD COLUMN bedrooms INT NULL AFTER floors;
-- ALTER TABLE auctions ADD COLUMN bathrooms INT NULL AFTER bedrooms;
-- ALTER TABLE auctions ADD COLUMN parking_spaces INT NULL AFTER bathrooms;
-- ALTER TABLE auctions ADD COLUMN year_built YEAR NULL AFTER parking_spaces;

-- Cek field facilities
-- ALTER TABLE auctions ADD COLUMN facilities TEXT NULL;
-- ALTER TABLE auctions ADD COLUMN nearby_facilities TEXT NULL;
-- ALTER TABLE auctions ADD COLUMN transportation_access TEXT NULL;

-- Update existing auctions untuk memastikan images minimal 3 (jika ada data)
-- Ini hanya untuk referensi, tidak perlu dijalankan jika belum ada data
/*
UPDATE auctions 
SET status = 'draft' 
WHERE (images IS NULL OR JSON_LENGTH(images) < 3) 
AND status != 'draft';
*/

-- Tambahkan index untuk performa yang lebih baik
CREATE INDEX IF NOT EXISTS idx_auctions_certificate_type ON auctions(certificate_type);
CREATE INDEX IF NOT EXISTS idx_auctions_land_area ON auctions(land_area);
CREATE INDEX IF NOT EXISTS idx_auctions_building_area ON auctions(building_area);
CREATE INDEX IF NOT EXISTS idx_auctions_year_built ON auctions(year_built);

-- Tambahkan constraint untuk memastikan data yang valid
-- ALTER TABLE auctions ADD CONSTRAINT chk_land_area_positive CHECK (land_area >= 0 OR land_area IS NULL);
-- ALTER TABLE auctions ADD CONSTRAINT chk_building_area_positive CHECK (building_area >= 0 OR building_area IS NULL);
-- ALTER TABLE auctions ADD CONSTRAINT chk_floors_positive CHECK (floors >= 0 OR floors IS NULL);
-- ALTER TABLE auctions ADD CONSTRAINT chk_bedrooms_positive CHECK (bedrooms >= 0 OR bedrooms IS NULL);
-- ALTER TABLE auctions ADD CONSTRAINT chk_bathrooms_positive CHECK (bathrooms >= 0 OR bathrooms IS NULL);
-- ALTER TABLE auctions ADD CONSTRAINT chk_parking_positive CHECK (parking_spaces >= 0 OR parking_spaces IS NULL);

-- Contoh data sample untuk testing (opsional)
/*
INSERT INTO auctions (
    title, slug, auction_number, asset_type, address, city, 
    limit_price, auction_date, auction_type, auction_location,
    contact_person, contact_phone, status,
    certificate_type, certificate_number, land_area, building_area,
    floors, bedrooms, bathrooms, parking_spaces, year_built,
    building_condition, facilities, nearby_facilities, transportation_access,
    images, created_at, updated_at
) VALUES (
    'Rumah Mewah 2 Lantai di Pangkalpinang',
    'rumah-mewah-2-lantai-di-pangkalpinang',
    'LA-2026-001',
    'rumah',
    'Jl. Sudirman No. 123, Bukit Intan, Pangkalpinang',
    'Pangkalpinang',
    850000000,
    '2026-03-15 10:00:00',
    'eksekusi_hak_tanggungan',
    'Kantor BPRS Bangka Belitung',
    'Ahmad Suryadi',
    '0717-123456',
    'draft',
    'SHM',
    '12345/2023',
    120.00,
    80.00,
    2,
    3,
    2,
    1,
    2020,
    'baik',
    'Listrik PLN, Air PDAM, Internet, Taman, Pagar',
    'Sekolah, Rumah Sakit, Mall, Pasar',
    '5 menit ke jalan raya, 10 menit ke pusat kota',
    '["auctions/sample1.jpg", "auctions/sample2.jpg", "auctions/sample3.jpg"]',
    NOW(),
    NOW()
);
*/

-- Pesan sukses
SELECT 'Database enhancement untuk lelang agunan telah selesai!' as message;