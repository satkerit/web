<?php

namespace App\Services;

use App\Models\FinancingConfig;

class FinancingCalculatorService
{
    /**
     * Calculate financing installment using flat rate formula.
     *
     * Formula: Monthly = (Principal + (Principal × Margin × Tenor/12)) / Tenor
     *
     * @param int $principal The principal amount
     * @param float $marginRate Annual margin rate (e.g., 0.12 for 12%)
     * @param int $tenor Tenor in months
     * @return array{principal: int, margin_rate: float, tenor: int, monthly_installment: int, total_payment: int, total_margin: int}
     */
    public function calculate(int $principal, float $marginRate, int $tenor): array
    {
        // Flat rate formula: (Principal + (Principal × Margin × Tenor/12)) / Tenor
        $totalMarginRaw = $principal * $marginRate * ($tenor / 12);
        $totalPaymentRaw = $principal + $totalMarginRaw;
        $monthlyInstallmentRaw = $totalPaymentRaw / $tenor;

        // Round monthly installment first, then derive other values to ensure consistency
        // This ensures: total_payment = monthly_installment * tenor (Requirement 3.2)
        // And: total_margin = total_payment - principal (Requirement 3.3)
        $monthlyInstallment = (int) round($monthlyInstallmentRaw);
        $totalPayment = $monthlyInstallment * $tenor;
        $totalMargin = $totalPayment - $principal;

        return [
            'principal' => $principal,
            'margin_rate' => $marginRate,
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
