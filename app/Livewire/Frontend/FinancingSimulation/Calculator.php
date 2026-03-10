<?php

namespace App\Livewire\Frontend\FinancingSimulation;

use App\Models\FinancingConfig;
use App\Services\FinancingCalculatorService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Simulasi Pembiayaan')]
#[Layout('frontend.layouts.app')]
class Calculator extends Component
{
    public $financingType = '';
    public $principal = '';
    public $tenor = '';
    public $downPayment = '';
    public $projectedRevenue = '';
    public $result = null;
    public $configs = [];
    public $availableTenors = [];
    public $selectedConfig = null;

    public function mount()
    {
        $this->configs = FinancingConfig::getConfigs();

        // Set default financing type if configs exist
        if ($this->configs->isNotEmpty()) {
            $this->financingType = $this->configs->first()->id;
            $this->updateAvailableTenors();
        }
    }

    /**
     * Update available tenors when financing type changes
     */
    public function updatedFinancingType($value)
    {
        $this->updateAvailableTenors();
        $this->tenor = '';
        $this->downPayment = '';
        $this->projectedRevenue = '';
        $this->result = null;
    }

    /**
     * Update available tenors based on selected financing type
     */
    protected function updateAvailableTenors()
    {
        $this->selectedConfig = $this->configs->firstWhere('id', $this->financingType);
        $this->availableTenors = $this->selectedConfig?->available_tenors ?? [];
    }

    /**
     * Calculate financing installment
     */
    public function calculate()
    {
        $this->result = null;
        $this->resetValidation();

        // Clean principal first (remove formatting like dots, commas, spaces)
        $cleanPrincipal = (int) preg_replace('/[^0-9]/', '', $this->principal);

        // Clean tenor (remove any non-numeric characters)
        $cleanTenor = (int) preg_replace('/[^0-9]/', '', $this->tenor);

        // Clean down payment
        $cleanDownPayment = (int) preg_replace('/[^0-9]/', '', $this->downPayment);

        // Clean projected revenue
        $cleanProjectedRevenue = (int) preg_replace('/[^0-9]/', '', $this->projectedRevenue);

        // Validate financing type
        if (empty($this->financingType)) {
            $this->addError('financingType', 'Jenis pembiayaan wajib dipilih.');
            return;
        }

        // Validate principal manually (after cleaning)
        if (empty($this->principal) || $cleanPrincipal < 1) {
            $this->addError('principal', 'Jumlah pembiayaan wajib diisi.');
            return;
        }

        // Validate tenor manually (after cleaning)
        if (empty($this->tenor) || $cleanTenor < 1) {
            $this->addError('tenor', 'Jangka waktu wajib diisi.');
            return;
        }

        if ($cleanTenor > 60) {
            $this->addError('tenor', 'Jangka waktu maksimal 60 bulan.');
            return;
        }

        // Get selected config
        $config = $this->configs->firstWhere('id', $this->financingType);

        if (!$config) {
            $this->addError('financingType', 'Jenis pembiayaan tidak valid.');
            return;
        }

        // Validate principal range
        $service = new FinancingCalculatorService();

        if (!$service->validatePrincipal($cleanPrincipal, $config)) {
            $this->addError('principal', 'Jumlah pembiayaan harus antara Rp ' .
                number_format($config->min_principal, 0, ',', '.') . ' - Rp ' .
                number_format($config->max_principal, 0, ',', '.'));
            return;
        }

        // Validate down payment if enabled
        if ($config->dp_enabled && $cleanDownPayment > 0) {
            $dpPercentage = ($cleanDownPayment / $cleanPrincipal) * 100;

            $minDp = $config->dp_min_percentage ?? 0;
            $maxDp = $config->dp_max_percentage ?? 100;

            if ($dpPercentage < $minDp) {
                $this->addError('downPayment', 'DP minimal ' . number_format($minDp, 0) . '% dari jumlah pembiayaan (Rp ' . number_format($cleanPrincipal * $minDp / 100, 0, ',', '.') . ')');
                return;
            }

            if ($dpPercentage > $maxDp) {
                $this->addError('downPayment', 'DP maksimal ' . number_format($maxDp, 0) . '% dari jumlah pembiayaan (Rp ' . number_format($cleanPrincipal * $maxDp / 100, 0, ',', '.') . ')');
                return;
            }
        }

        // Validate projected revenue for profit sharing
        if ($config->isProfitSharing()) {
            if (empty($this->projectedRevenue) || $cleanProjectedRevenue < 1) {
                $this->addError('projectedRevenue', 'Proyeksi pendapatan wajib diisi untuk pembiayaan modal kerja.');
                return;
            }

            if ($cleanProjectedRevenue < $cleanPrincipal) {
                $this->addError('projectedRevenue', 'Proyeksi pendapatan harus lebih besar dari plafond pembiayaan.');
                return;
            }
        }

        // Calculate principal after DP
        $principalAfterDp = $cleanPrincipal - $cleanDownPayment;

        // Calculate
        $this->result = $service->calculate(
            $principalAfterDp,
            (float) $config->margin_rate,
            $cleanTenor,
            $config->calculation_type,
            $cleanProjectedRevenue
        );

        // Add config info to result
        $this->result['config_name'] = $config->name;
        $this->result['calculation_type'] = $config->calculation_type;
        $this->result['rate_label'] = $config->getRateLabel();
        $this->result['margin_percentage'] = $config->margin_rate * 100;
        $this->result['monthly_margin_percentage'] = ($config->margin_rate / 12) * 100;
        $this->result['original_principal'] = $cleanPrincipal;
        $this->result['down_payment'] = $cleanDownPayment;
        $this->result['dp_percentage'] = $cleanPrincipal > 0 ? round(($cleanDownPayment / $cleanPrincipal) * 100, 2) : 0;
        
        if ($config->isProfitSharing() && $cleanProjectedRevenue > 0) {
            $this->result['projected_revenue'] = $cleanProjectedRevenue;
        }
    }

    /**
     * Reset calculator
     */
    public function resetCalculator()
    {
        $this->principal = '';
        $this->tenor = '';
        $this->downPayment = '';
        $this->projectedRevenue = '';
        $this->result = null;
        $this->resetValidation();
    }

    /**
     * Format number to Indonesian Rupiah with optimal scale
     */
    public function formatRupiah($number): string
    {
        return \App\Helpers\CurrencyFormatter::formatOptimal($number);
    }

    /**
     * Format number to full Rupiah format
     */
    public function formatRupiahFull($number): string
    {
        return \App\Helpers\CurrencyFormatter::formatFull($number);
    }

    public function render()
    {
        return view('livewire.frontend.financing-simulation.calculator');
    }
}
