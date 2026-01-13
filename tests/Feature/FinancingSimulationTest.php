<?php

namespace Tests\Feature;

use App\Models\FinancingConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature tests for Frontend Financing Simulation.
 *
 * **Validates: Requirements 1.1, 1.2, 1.4, 1.5**
 */
class FinancingSimulationTest extends TestCase
{
    use RefreshDatabase;

    private FinancingConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test financing config
        $this->config = FinancingConfig::create([
            'type' => 'murabahah',
            'name' => 'Pembiayaan Murabahah',
            'margin_rate' => 0.12,
            'min_principal' => 5000000,
            'max_principal' => 500000000,
            'available_tenors' => [12, 24, 36, 48, 60],
            'is_active' => true,
        ]);
    }

    #[Test]
    public function simulation_page_is_accessible(): void
    {
        $response = $this->withoutSecurityMiddleware()
            ->get(route('financing-simulation'));

        $response->assertStatus(200);
    }

    #[Test]
    public function calculator_component_loads_with_configs(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->assertSee('Pembiayaan Murabahah')
            ->assertSee('Kalkulator Simulasi');
    }

    #[Test]
    public function calculation_returns_correct_results(): void
    {
        // Test with principal 100,000,000, margin 12%, tenor 36 months
        // Expected: Monthly = (100M + (100M × 0.12 × 36/12)) / 36 = (100M + 36M) / 36 = 3,777,778
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '100000000')
            ->set('tenor', 36)
            ->call('calculate')
            ->assertSet('result.monthly_installment', 3777778)
            ->assertSet('result.total_payment', 136000008)
            ->assertSet('result.total_margin', 36000008);
    }

    #[Test]
    public function validation_error_for_empty_principal(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '')
            ->set('tenor', 12)
            ->call('calculate')
            ->assertHasErrors(['principal']);
    }

    #[Test]
    public function validation_error_for_principal_below_minimum(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '1000000') // Below min of 5,000,000
            ->set('tenor', 12)
            ->call('calculate')
            ->assertHasErrors(['principal']);
    }

    #[Test]
    public function validation_error_for_principal_above_maximum(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '600000000') // Above max of 500,000,000
            ->set('tenor', 12)
            ->call('calculate')
            ->assertHasErrors(['principal']);
    }

    #[Test]
    public function validation_error_for_invalid_tenor(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '50000000')
            ->set('tenor', 61) // Above max of 60 months
            ->call('calculate')
            ->assertHasErrors(['tenor']);
    }

    #[Test]
    public function reset_calculator_clears_result(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '50000000')
            ->set('tenor', 12)
            ->call('calculate')
            ->assertSet('result.monthly_installment', 4666667)
            ->call('resetCalculator')
            ->assertSet('result', null)
            ->assertSet('principal', '')
            ->assertSet('tenor', '');
    }

    #[Test]
    public function calculation_works_with_formatted_principal(): void
    {
        // Test with formatted principal (with dots as thousand separator)
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '50.000.000') // Formatted with dots
            ->set('tenor', 24)
            ->call('calculate')
            ->assertHasNoErrors()
            ->assertSet('result.principal', 50000000);
    }

    #[Test]
    public function validation_error_for_tenor_below_minimum(): void
    {
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '50000000')
            ->set('tenor', 0) // Below min of 1 month
            ->call('calculate')
            ->assertHasErrors(['tenor']);
    }

    #[Test]
    public function calculation_works_with_any_tenor_up_to_60(): void
    {
        // Test with tenor 18 (which was not in available_tenors before)
        Livewire::test(\App\Livewire\Frontend\FinancingSimulation\Calculator::class)
            ->set('financingType', $this->config->id)
            ->set('principal', '50000000')
            ->set('tenor', 18) // Any tenor between 1-60 should work now
            ->call('calculate')
            ->assertHasNoErrors()
            ->assertSet('result.tenor', 18);
    }
}
