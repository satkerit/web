# Requirements Document

## Introduction

Dokumen ini mendefinisikan requirements untuk fitur Simulasi Pembiayaan pada website company. Fitur ini memungkinkan pengunjung untuk menghitung estimasi angsuran pembiayaan berdasarkan parameter yang diinput, serta admin dapat mengatur konfigurasi perhitungan seperti margin/rate dan tenor yang tersedia.

## Glossary

-   **Simulation_Calculator**: Komponen frontend yang menghitung dan menampilkan estimasi angsuran pembiayaan
-   **Financing_Config**: Konfigurasi pembiayaan yang dikelola admin (margin, tenor, plafon min/max)
-   **Principal**: Jumlah pokok pembiayaan yang diajukan
-   **Margin**: Persentase keuntungan/margin yang diterapkan pada pembiayaan (sistem syariah)
-   **Tenor**: Jangka waktu pembiayaan dalam bulan
-   **Monthly_Installment**: Angsuran bulanan yang harus dibayar
-   **Admin_User**: User dengan role admin/super_admin yang dapat mengatur konfigurasi pembiayaan
-   **Visitor**: Pengunjung website yang menggunakan simulasi pembiayaan

## Requirements

### Requirement 1: Frontend Simulation Calculator

**User Story:** As a visitor, I want to calculate my financing installment estimation, so that I can plan my budget before applying for financing.

#### Acceptance Criteria

1. WHEN a visitor accesses the financing simulation page THEN THE Simulation_Calculator SHALL display input fields for principal amount, tenor selection, and financing type
2. WHEN a visitor inputs a valid principal amount THEN THE Simulation_Calculator SHALL validate the amount is within configured min/max range
3. WHEN a visitor selects a tenor THEN THE Simulation_Calculator SHALL only show tenor options configured by admin
4. WHEN a visitor clicks calculate button with valid inputs THEN THE Simulation_Calculator SHALL display monthly installment, total payment, and total margin
5. WHEN a visitor inputs invalid data THEN THE Simulation_Calculator SHALL display appropriate validation error messages
6. THE Simulation_Calculator SHALL format currency values in Indonesian Rupiah format (Rp)
7. THE Simulation_Calculator SHALL perform calculation without page reload using JavaScript/Livewire

### Requirement 2: Admin Financing Configuration

**User Story:** As an admin, I want to configure financing calculation parameters, so that I can control the simulation results based on current business rules.

#### Acceptance Criteria

1. WHEN an admin accesses financing config page THEN THE System SHALL display current configuration settings
2. WHEN an admin updates margin rate THEN THE System SHALL validate margin is a positive decimal number
3. WHEN an admin updates minimum principal THEN THE System SHALL validate it is a positive integer
4. WHEN an admin updates maximum principal THEN THE System SHALL validate it is greater than minimum principal
5. WHEN an admin updates available tenors THEN THE System SHALL accept array of months (e.g., 12, 24, 36, 48, 60)
6. WHEN an admin saves configuration THEN THE System SHALL persist changes and clear related cache
7. THE System SHALL restrict financing config access to admin and super_admin roles only

### Requirement 3: Calculation Logic

**User Story:** As a system, I want to calculate financing installments accurately, so that visitors get reliable estimation.

#### Acceptance Criteria

1. THE System SHALL calculate monthly installment using flat rate formula: (Principal + (Principal × Margin% × Tenor/12)) / Tenor
2. THE System SHALL calculate total payment as: Monthly_Installment × Tenor
3. THE System SHALL calculate total margin as: Total_Payment - Principal
4. WHEN calculation is performed THEN THE System SHALL round results to nearest integer (no decimal for Rupiah)
5. THE System SHALL support multiple financing types with different margin rates

### Requirement 4: Data Persistence

**User Story:** As a system, I want to store financing configurations, so that settings persist across sessions.

#### Acceptance Criteria

1. THE System SHALL store financing configurations in database table
2. THE System SHALL cache configuration data for performance
3. WHEN configuration is updated THEN THE System SHALL invalidate related cache
4. THE System SHALL provide default configuration values if none exists
