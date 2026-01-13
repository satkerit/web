# Implementation Plan: Financing Simulation

## Overview

Implementasi fitur Simulasi Pembiayaan untuk frontend calculator dan backend admin configuration. Tasks disusun secara incremental dari database setup hingga frontend implementation.

## Tasks

-   [x] 1. Database Setup

    -   [x] 1.1 Create financing_configs migration
        -   Create table with columns: type, name, margin_rate, min_principal, max_principal, available_tenors, is_active
        -   Add timestamps
        -   _Requirements: 4.1_
    -   [x] 1.2 Create FinancingConfig model
        -   Define fillable fields
        -   Define casts for margin_rate (decimal), available_tenors (array), is_active (boolean)
        -   Add cache methods (getConfigs, clearCache)
        -   _Requirements: 4.2, 4.3, 4.4_
    -   [x] 1.3 Create database seeder with default config
        -   Add default Murabahah config with 12% margin
        -   Add default Musyarakah config with 10% margin
        -   _Requirements: 4.4_

-   [x] 2. Calculation Service

    -   [x] 2.1 Create FinancingCalculatorService
        -   Implement calculate() method with flat rate formula
        -   Implement validatePrincipal() method
        -   Return results as array with monthly_installment, total_payment, total_margin
        -   _Requirements: 3.1, 3.2, 3.3, 3.4_
    -   [x] 2.2 Write property test for calculation correctness
        -   **Property 1: Calculation Formula Correctness**
        -   **Validates: Requirements 3.1, 3.4**
    -   [x] 2.3 Write property test for total payment and margin consistency
        -   **Property 2: Total Payment Consistency**
        -   **Property 3: Total Margin Consistency**
        -   **Validates: Requirements 3.2, 3.3**

-   [x] 3. Checkpoint - Core Logic

    -   Ensure calculation service tests pass, ask the user if questions arise.

-   [x] 4. Admin Backend

    -   [x] 4.1 Create FinancingConfigController
        -   Implement index() to list all configs
        -   Implement edit() to show edit form
        -   Implement update() with validation
        -   Add authorization check for admin/super_admin
        -   _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_
    -   [x] 4.2 Create admin views for financing config
        -   Create resources/views/admin/financing-config/index.blade.php with config list
        -   Create resources/views/admin/financing-config/form.blade.php with edit form
        -   Add validation error display
        -   Style consistent with existing admin views
        -   _Requirements: 2.1_
    -   [x] 4.3 Add admin routes
        -   Add routes for financing config (index, edit, update) in routes/web.php
        -   Apply auth, role, and admin.ddos middleware
        -   _Requirements: 2.7_
    -   [x] 4.4 Add menu item to admin sidebar
        -   Add "Simulasi Pembiayaan" menu in resources/views/layouts/admin/menu.blade.php
        -   Place under "Sistem" section (requires canManageSettings)
        -   _Requirements: 2.1_

-   [x] 5. Checkpoint - Admin Backend

    -   Ensure admin can access and update financing config, ask the user if questions arise.

-   [x] 6. Frontend Calculator

    -   [x] 6.1 Create Livewire Calculator component
        -   Create app/Livewire/Frontend/FinancingSimulation/Calculator.php
        -   Implement mount() to load active configs
        -   Implement calculate() method with validation
        -   Implement updatedFinancingType() to update available tenors
        -   _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.7_
    -   [x] 6.2 Create calculator blade view
        -   Create resources/views/livewire/frontend/financing-simulation/calculator.blade.php
        -   Add principal input with currency formatting
        -   Add financing type dropdown
        -   Add tenor selection (radio or dropdown)
        -   Add result display section with Rp formatting
        -   Style with Tailwind CSS matching existing design
        -   _Requirements: 1.1, 1.4, 1.6_
    -   [x] 6.3 Create financing simulation page
        -   Create resources/views/frontend/pages/financing-simulation.blade.php
        -   Include Livewire calculator component
        -   Add page title and description
        -   _Requirements: 1.1_
    -   [x] 6.4 Add frontend route
        -   Add route for /simulasi-pembiayaan in routes/web.php
        -   _Requirements: 1.1_
    -   [x] 6.5 Add navigation link
        -   Add link to simulation page in product menu or footer
        -   _Requirements: 1.1_

-   [x] 7. Checkpoint - Frontend Calculator

    -   Ensure calculator works correctly, ask the user if questions arise.

-   [x] 8. Feature Tests

    -   [x] 8.1 Create FinancingConfigTest for admin
        -   Test admin can view config list
        -   Test admin can update config
        -   Test validation errors
        -   Test editor cannot access config
        -   _Requirements: 2.1, 2.2, 2.3, 2.4, 2.7_
    -   [x] 8.2 Create FinancingSimulationTest for frontend
        -   Test simulation page is accessible
        -   Test calculation returns correct results
        -   Test validation errors for invalid input
        -   _Requirements: 1.1, 1.2, 1.4, 1.5_

-   [x] 9. Final Checkpoint
    -   Ensure all tests pass
    -   Ask the user if questions arise

## Notes

-   Tasks marked with `*` are optional and can be skipped for faster MVP
-   Each task references specific requirements for traceability
-   Checkpoints ensure incremental validation
-   Use `php artisan test` to run all tests
-   Formula: Monthly = (Principal + (Principal × Margin × Tenor/12)) / Tenor
