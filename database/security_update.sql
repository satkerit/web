-- Pengecekan dan pembuatan tabel security_logs
SET @tableName = 'security_logs';

SET @dbName = DATABASE();

SET
    @tableExists = (
        SELECT COUNT(*)
        FROM information_schema.tables
        WHERE
            table_schema = @dbName
            AND table_name = @tableName
    );

-- Buat tabel security_logs jika belum ada
SET
    @sql = IF(
        @tableExists = 0,
        'CREATE TABLE security_logs (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        user_agent VARCHAR(500) NULL,
        request_method VARCHAR(10) NOT NULL,
        request_url VARCHAR(2048) NOT NULL,
        payload TEXT NULL,
        threat_type VARCHAR(50) NOT NULL,
        threat_level VARCHAR(20) NOT NULL DEFAULT "medium",
        matched_pattern TEXT NULL,
        raw_input TEXT NULL,
        user_id BIGINT UNSIGNED NULL,
        country_code VARCHAR(5) NULL,
        session_id VARCHAR(100) NULL,
        was_blocked BOOLEAN NOT NULL DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX security_logs_ip_address_index (ip_address),
        INDEX security_logs_threat_type_index (threat_type),
        INDEX security_logs_threat_level_index (threat_level),
        INDEX security_logs_created_at_index (created_at),
        INDEX security_logs_ip_address_created_at_index (ip_address, created_at)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;',
        'SELECT "Tabel security_logs sudah ada, melewati proses pembuatan table."'
    );

PREPARE stmt FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;

-- Pengecekan dan penambahan kolom attack_count ke tabel blocked_ips
SET @tableName = 'blocked_ips';

SET @columnName = 'attack_count';

SET
    @columnExists = (
        SELECT COUNT(*)
        FROM information_schema.columns
        WHERE
            table_schema = @dbName
            AND table_name = @tableName
            AND column_name = @columnName
    );

-- Tambahkan kolom attack_count jika belum ada
SET
    @sql = IF(
        @columnExists = 0,
        'ALTER TABLE blocked_ips ADD COLUMN attack_count INT NOT NULL DEFAULT 0 AFTER attempts;',
        'SELECT "Kolom attack_count sudah ada di tabel blocked_ips, melewati penambahan kolom."'
    );

PREPARE stmt FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;
