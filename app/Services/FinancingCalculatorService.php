<?php

namespace App\Services;

use App\Models\FinancingConfig;

class FinancingCalculatorService
{
    /**
     * Calculate financing installment using monthly margin rate formula.
     *
     * Formula:
     * - Monthly Margin Rate = Annual Margin Rate / 12
     * - Total Margin = Principal × Monthly Margin Rate × Tenor
     * - Total Payment = Principal + Total Margin
     * - Monthly Installment = Total Payment / Tenor
     *
     * For Profit Sharing (Modal Kerja):
     * - Total Profit Sharing = Projected Revenue × Profit Share Rate × Tenor (in months) / 12
     * - Monthly Installment = (Principal + Total Profit Sharing) / Tenor
     *
     * Example: Principal 50.000.000, Annual Rate 12%, Tenor 9 bulan
     * - Monthly Rate = 12% / 12 = 1% per bulan
     * - Total Margin = 50.000.000 × 1% × 9 = 4.500.000
     * - Total Payment = 50.000.000 + 4.500.000 = 54.500.000
     * - Monthly Installment = 54.500.000 / 9 = 6.055.556
     *
     * @param int $principal The principal amount
     * @param float $marginRate Annual margin rate (e.g., 0.12 for 12%)
     * @param int $tenor Tenor in months
     * @param string $calculationType Type of calculation: 'margin' or 'profit_sharing'
     * @param int $projectedRevenue Projected revenue for profit sharing (optional)
     * @return array{principal: int, margin_rate: float, monthly_margin_rate: float, tenor: int, monthly_installment: int, total_payment: int, total_margin: int}
     */
    public function calculate(
        int $principal, 
        float $marginRate, 
        int $tenor, 
        string $calculationType = 'margin',
        int $projectedRevenue = 0
    ): array
    {
        // Calculate monthly margin rate from annual rate
        $monthlyMarginRate = $marginRate / 12;
        
        // Calculate based on calculation type
        if ($calculationType === 'profit_sharing' && $projectedRevenue > 0) {
            // For profit sharing, calculate based on projected revenue
            // Total profit sharing = Projected Revenue × Annual Rate × (Tenor / 12)
            $totalMarginRaw = $projectedRevenue * $marginRate * ($tenor / 12);
        } else {
            // For margin, calculate based on principal
            // Total margin: Principal × Monthly Rate × Tenor
            $totalMarginRaw = $principal * $monthlyMarginRate * $tenor;
        }
        
        // Calculate total payment
        $totalPaymentRaw = $principal + $totalMarginRaw;
        
        // Calculate monthly installment
        $monthlyInstallmentRaw = $totalPaymentRaw / $tenor;

        // Round monthly installment first, then derive other values to ensure consistency
        // This ensures: total_payment = monthly_installment * tenor
        // And: total_margin = total_payment - principal
        $monthlyInstallment = (int) round($monthlyInstallmentRaw);
        $totalPayment = $monthlyInstallment * $tenor;
        $totalMargin = $totalPayment - $principal;

        return [
            'principal' => $principal,
            'margin_rate' => $marginRate,
            'monthly_margin_rate' => $monthlyMarginRate,
            'tenor' => $tenor,
            'monthly_installment' => $monthlyInstallment,
            'total_payment' => $totalPayment,
            'total_margin' => $totalMargin,
        ];
    }

    /**
     * Validate if principal amount is within configured range.
     *
     * @param int $principal The principal amount to validate
     * @param FinancingConfig $config The financing configuration
     * @return bool True if principal is within range, false otherwise
     */
    public function validatePrincipal(int $principal, FinancingConfig $config): bool
    {
        return $principal >= $config->min_principal
            && $principal <= $config->max_principal;
    }
}
