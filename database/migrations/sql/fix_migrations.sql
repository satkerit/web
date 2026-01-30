-- Script untuk menandai migration yang sudah ada sebagai completed
-- Jalankan script ini jika ada error "Column already exists" atau "Table already exists"

SET @batch = ( SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations );

-- Tandai migration yang bermasalah sebagai sudah dijalankan
INSERT IGNORE INTO
    migrations (migration, batch)
VALUES (
        '2026_01_13_141855_add_logo_footer_to_company_infos_table',
        @batch
    ),
    (
        '2026_01_13_150949_add_logo_footer_remove_bg_to_company_infos_table',
        @batch
    ),
    (
        '2026_01_13_151507_add_logo_footer_opacity_to_company_infos_table',
        @batch
    ),
    (
        '2026_01_18_133831_create_why_choose_us_table',
        @batch
    ),
    (
        '2026_01_29_232500_create_security_logs_table',
        @batch
    );

-- Verifikasi
SELECT * FROM migrations ORDER BY id DESC LIMIT 10;
