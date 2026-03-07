<?php

namespace Tests\Unit\Services;

use App\Services\FinancingCalculatorService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Property-based tests for FinancingCalculatorService.
 *
 * Feature: financing-simulation
 */
class FinancingCalculatorServiceTest extends TestCase
{
    private FinancingCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FinancingCalculatorService();
    }

    /**
     * Property 1: Calculation Formula Correctness
     *
     * For any valid principal amount, margin rate, and tenor, the calculated
     * monthly installment SHALL equal (Principal + (Principal × MarginRate × Tenor/12)) / Tenor,
     * rounded to nearest integer.
     *
     * **Validates: Requirements 3.1, 3.4**
     */
    #[DataProvider('calculationFormulaProvider')]
    public function test_calculation_formula_correctness(int $principal, float $marginRate, int $tenor): void
    {
        $result = $this->service->calculate($principal, $marginRate, $tenor, 'margin', 0);

        // Calculate expected values using the flat rate formula
        $expectedTotalMargin = $principal * $marginRate * ($tenor / 12);
        $expectedTotalPayment = $principal + $expectedTotalMargin;
        $expectedMonthlyInstallment = $expectedTotalPayment / $tenor;

        // Verify monthly installment matches formula (rounded to nearest integer)
        $this->assertEquals(
            (int) round($expectedMonthlyInstallment),
            $result['monthly_installment'],
            "Monthly installment should equal (Principal + (Principal × MarginRate × Tenor/12)) / Tenor, rounded"
        );

        // Verify the result is an integer (no decimal for Rupiah - Requirement 3.4)
        $this->assertIsInt(
            $result['monthly_installment'],
            "Monthly installment should be an integer (no decimal for Rupiah)"
        );
    }

    /**
     * Data provider for calculation formula correctness property test.
     * Generates 100+ random test cases to satisfy property-based testing requirements.
     *
     * @return array<string, array{int, float, int}>
     */
    public static function calculationFormulaProvider(): array
    {
        $testCases = [];

        // Generate 100 random test cases
        for ($i = 0; $i < 100; $i++) {
            // Principal: random amount between 1,000,000 and 1,000,000,000 (1 million to 1 billion)
            $principal = mt_rand(1000000, 1000000000);

            // Margin rate: random rate between 0.01 (1%) and 0.30 (30%)
            $marginRate = mt_rand(100, 3000) / 10000;

            // Tenor: random months from common financing tenors
            $tenors = [6, 12, 18, 24, 30, 36, 48, 60, 72, 84, 96, 120];
            $tenor = $tenors[array_rand($tenors)];

            $testCases["principal_{$principal}_margin_{$marginRate}_tenor_{$tenor}"] = [
                $principal,
                $marginRate,
                $tenor,
            ];
        }

        // Add specific edge cases
        $testCases['minimum_values'] = [1000000, 0.01, 6];
        $testCases['maximum_values'] = [500000000, 0.25, 120];
        $testCases['typical_murabahah'] = [100000000, 0.12, 36];
        $testCases['typical_musyarakah'] = [50000000, 0.10, 24];
        $testCases['small_principal_long_tenor'] = [5000000, 0.15, 60];
        $testCases['large_principal_short_tenor'] = [300000000, 0.08, 12];

        return $testCases;
    }

    /**
     * Property 2: Total Payment Consistency
     *
     * For any calculation result, the total payment SHALL equal monthly installment
     * multiplied by tenor.
     *
     * **Validates: Requirements 3.2**
     */
    #[DataProvider('totalPaymentConsistencyProvider')]
    public function test_total_payment_consistency(int $principal, float $marginRate, int $tenor): void
    {
        $result = $this->service->calculate($principal, $marginRate, $tenor, 'margin', 0);

        // Total payment should equal monthly installment × tenor
        $expectedTotalPayment = $result['monthly_installment'] * $tenor;

        $this->assertEquals(
            $expectedTotalPayment,
            $result['total_payment'],
            "Total payment should equal monthly installment × tenor"
        );

        // Verify the result is an integer (no decimal for Rupiah)
        $this->assertIsInt(
            $result['total_payment'],
            "Total payment should be an integer (no decimal for Rupiah)"
        );
    }

    /**
     * Data provider for total payment consistency property test.
     * Generates 100+ random test cases to satisfy property-based testing requirements.
     *
     * @return array<string, array{int, float, int}>
     */
    public static function totalPaymentConsistencyProvider(): array
    {
        $testCases = [];

        // Generate 100 random test cases
        for ($i = 0; $i < 100; $i++) {
            $principal = mt_rand(1000000, 1000000000);
            $marginRate = mt_rand(100, 3000) / 10000;
            $tenors = [6, 12, 18, 24, 30, 36, 48, 60, 72, 84, 96, 120];
            $tenor = $tenors[array_rand($tenors)];

            $testCases["case_{$i}"] = [$principal, $marginRate, $tenor];
        }

        // Add specific edge cases
        $testCases['minimum_values'] = [1000000, 0.01, 6];
        $testCases['maximum_values'] = [500000000, 0.25, 120];
        $testCases['typical_murabahah'] = [100000000, 0.12, 36];
        $testCases['typical_musyarakah'] = [50000000, 0.10, 24];

        return $testCases;
    }

    /**
     * Property 3: Total Margin Consistency
     *
     * For any calculation result, the total margin SHALL equal total payment minus principal.
     *
     * **Validates: Requirements 3.3**
     */
    #[DataProvider('totalMarginConsistencyProvider')]
    public function test_total_margin_consistency(int $principal, float $marginRate, int $tenor): void
    {
        $result = $this->service->calculate($principal, $marginRate, $tenor, 'margin', 0);

        // Total margin should equal total payment - principal
        $expectedTotalMargin = $result['total_payment'] - $principal;

        $this->assertEquals(
            $expectedTotalMargin,
            $result['total_margin'],
            "Total margin should equal total payment minus principal"
        );

        // Verify the result is an integer (no decimal for Rupiah)
        $this->assertIsInt(
            $result['total_margin'],
            "Total margin should be an integer (no decimal for Rupiah)"
        );
    }

    /**
     * Test profit sharing calculation based on projected revenue
     *
     * For profit sharing financing, the calculation should be based on
     * projected revenue instead of principal amount.
     */
    public function test_profit_sharing_calculation_with_projected_revenue(): void
    {
        $principal = 50000000; // 50 million
        $projectedRevenue = 100000000; // 100 million per year
        $marginRate = 0.12; // 12% per year
        $tenor = 12; // 12 months

        $result = $this->service->calculate($principal, $marginRate, $tenor, 'profit_sharing', $projectedRevenue);

        // For profit sharing: Total margin = Projected Revenue × Annual Rate × (Tenor / 12)
        $expectedTotalMargin = $projectedRevenue * $marginRate * ($tenor / 12);
        $expectedTotalPayment = $principal + $expectedTotalMargin;
        $expectedMonthlyInstallment = $expectedTotalPayment / $tenor;

        $this->assertEquals(
            (int) round($expectedMonthlyInstallment),
            $result['monthly_installment'],
            "Monthly installment for profit sharing should be based on projected revenue"
        );

        // Verify total margin is based on projected revenue, not principal
        $this->assertGreaterThan(
            $principal * $marginRate * ($tenor / 12),
            $result['total_margin'],
            "Total margin should be higher when based on projected revenue vs principal"
        );
    }

    /**
     * Test margin calculation (default behavior)
     */
    public function test_margin_calculation_based_on_principal(): void
    {
        $principal = 50000000; // 50 million
        $marginRate = 0.12; // 12% per year
        $tenor = 12; // 12 months

        $result = $this->service->calculate($principal, $marginRate, $tenor, 'margin', 0);

        // For margin: Total margin = Principal × Monthly Rate × Tenor
        $monthlyRate = $marginRate / 12;
        $expectedTotalMargin = $principal * $monthlyRate * $tenor;
        $expectedTotalPayment = $principal + $expectedTotalMargin;
        $expectedMonthlyInstallment = $expectedTotalPayment / $tenor;

        $this->assertEquals(
            (int) round($expectedMonthlyInstallment),
            $result['monthly_installment'],
            "Monthly installment for margin should be based on principal"
        );
    }

    /**
     * Data provider for total margin consistency property test.
     * Generates 100+ random test cases to satisfy property-based testing requirements.
     *
     * @return array<string, array{int, float, int}>
     */
    public static function totalMarginConsistencyProvider(): array
    {
        $testCases = [];

        // Generate 100 random test cases
        for ($i = 0; $i < 100; $i++) {
            $principal = mt_rand(1000000, 1000000000);
            $marginRate = mt_rand(100, 3000) / 10000;
            $tenors = [6, 12, 18, 24, 30, 36, 48, 60, 72, 84, 96, 120];
            $tenor = $tenors[array_rand($tenors)];

            $testCases["case_{$i}"] = [$principal, $marginRate, $tenor];
        }

        // Add specific edge cases
        $testCases['minimum_values'] = [1000000, 0.01, 6];
        $testCases['maximum_values'] = [500000000, 0.25, 120];
        $testCases['typical_murabahah'] = [100000000, 0.12, 36];
        $testCases['typical_musyarakah'] = [50000000, 0.10, 24];

        return $testCases;
    }
}
