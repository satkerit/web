# Design Document: Financing Simulation

## Overview

Fitur Simulasi Pembiayaan memungkinkan pengunjung website untuk menghitung estimasi angsuran pembiayaan secara real-time. Admin dapat mengkonfigurasi parameter perhitungan seperti margin rate, tenor yang tersedia, dan batas plafon. Sistem menggunakan formula flat rate yang umum digunakan pada pembiayaan syariah.

## Architecture

```
app/
├── Models/
│   └── FinancingConfig.php          # Model untuk konfigurasi pembiayaan
├── Services/
│   └── FinancingCalculatorService.php # Service untuk logic perhitungan
├── Http/Controllers/
│   └── Admin/
│       └── FinancingConfigController.php # Controller admin untuk config
├── Livewire/
│   └── Frontend/
│       └── FinancingSimulation/
│           └── Calculator.php        # Livewire component untuk calculator
│
database/
├── migrations/
│   └── create_financing_configs_table.php
│
resources/views/
├── frontend/pages/
│   └── financing-simulation.blade.php
├── livewire/frontend/financing-simulation/
│   └── calculator.blade.php
├── admin/financing-config/
│   └── form.blade.php
```

## Components and Interfaces

### 1. FinancingConfig Model

```php
class FinancingConfig extends Model
{
    protected $fillable = [
        'type',           // 'murabahah', 'musyarakah', etc.
        'name',           // Display name
        'margin_rate',    // Decimal (e.g., 0.12 for 12%)
        'min_principal',  // Minimum loan amount
        'max_principal',  // Maximum loan amount
        'available_tenors', // JSON array [12, 24, 36, 48, 60]
        'is_active',
    ];

    protected $casts = [
        'margin_rate' => 'decimal:4',
        'available_tenors' => 'array',
        'is_active' => 'boolean',
    ];
}
```

### 2. FinancingCalculatorService

```php
class FinancingCalculatorService
{
    public function calculate(int $principal, float $marginRate, int $tenor): array
    {
        // Flat rate formula: (Principal + (Principal × Margin × Tenor/12)) / Tenor
        $totalMargin = $principal * $marginRate * ($tenor / 12);
        $totalPayment = $principal + $totalMargin;
        $monthlyInstallment = $totalPayment / $tenor;

        return [
            'principal' => $principal,
            'margin_rate' => $marginRate,
            'tenor' => $tenor,
            'monthly_installment' => (int) round($monthlyInstallment),
            'total_payment' => (int) round($totalPayment),
            'total_margin' => (int) round($totalMargin),
        ];
    }

    public function validatePrincipal(int $principal, FinancingConfig $config): bool
    {
        return $principal >= $config->min_principal
            && $principal <= $config->max_principal;
    }
}
```

### 3. Livewire Calculator Component

```php
class Calculator extends Component
{
    public $financingType = '';
    public $principal = '';
    public $tenor = '';
    public $result = null;
    public $configs = [];

    public function mount()
    {
        $this->configs = FinancingConfig::where('is_active', true)->get();
    }

    public function calculate()
    {
        $this->validate([
            'financingType' => 'required|exists:financing_configs,id',
            'principal' => 'required|numeric|min:1',
            'tenor' => 'required|integer|min:1',
        ]);

        $config = FinancingConfig::find($this->financingType);
        $service = new FinancingCalculatorService();

        if (!$service->validatePrincipal((int) $this->principal, $config)) {
            $this->addError('principal', 'Jumlah pembiayaan harus antara ' .
                number_format($config->min_principal) . ' - ' .
                number_format($config->max_principal));
            return;
        }

        $this->result = $service->calculate(
            (int) $this->principal,
            (float) $config->margin_rate,
            (int) $this->tenor
        );
    }
}
```

## Data Models

### FinancingConfig Table Schema

| Column           | Type         | Description                                 |
| ---------------- | ------------ | ------------------------------------------- |
| id               | bigint       | Primary key                                 |
| type             | string       | Financing type code (murabahah, musyarakah) |
| name             | string       | Display name                                |
| margin_rate      | decimal(8,4) | Annual margin rate (e.g., 0.1200 = 12%)     |
| min_principal    | bigint       | Minimum principal amount                    |
| max_principal    | bigint       | Maximum principal amount                    |
| available_tenors | json         | Array of available tenors in months         |
| is_active        | boolean      | Whether this config is active               |
| created_at       | timestamp    |                                             |
| updated_at       | timestamp    |                                             |

### Default Configuration

```php
[
    'type' => 'murabahah',
    'name' => 'Pembiayaan Murabahah',
    'margin_rate' => 0.12, // 12% per year
    'min_principal' => 5000000,    // Rp 5 juta
    'max_principal' => 500000000,  // Rp 500 juta
    'available_tenors' => [12, 24, 36, 48, 60],
    'is_active' => true,
]
```

## Correctness Properties

_A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees._

### Property 1: Calculation Formula Correctness

_For any_ valid principal amount, margin rate, and tenor, the calculated monthly installment SHALL equal (Principal + (Principal × MarginRate × Tenor/12)) / Tenor, rounded to nearest integer.

**Validates: Requirements 3.1, 3.4**

### Property 2: Total Payment Consistency

_For any_ calculation result, the total payment SHALL equal monthly installment multiplied by tenor.

**Validates: Requirements 3.2**

### Property 3: Total Margin Consistency

_For any_ calculation result, the total margin SHALL equal total payment minus principal.

**Validates: Requirements 3.3**

### Property 4: Principal Validation Range

_For any_ principal amount, validation SHALL pass if and only if the amount is within the configured min_principal and max_principal range (inclusive).

**Validates: Requirements 1.2**

### Property 5: Currency Formatting

_For any_ monetary value displayed, the format SHALL include "Rp" prefix and use Indonesian number formatting (dot as thousand separator).

**Validates: Requirements 1.6**

### Property 6: Admin Authorization

_For any_ request to financing config routes, access SHALL be granted only to users with admin or super_admin role.

**Validates: Requirements 2.7**

### Property 7: Margin Rate Validation

_For any_ margin rate input, validation SHALL pass if and only if the value is a positive decimal number.

**Validates: Requirements 2.2**

### Property 8: Principal Range Validation

_For any_ min/max principal configuration, validation SHALL pass if and only if max_principal is greater than min_principal.

**Validates: Requirements 2.4**

## Error Handling

### Validation Errors

```php
// Principal validation
'principal.required' => 'Jumlah pembiayaan wajib diisi.',
'principal.numeric' => 'Jumlah pembiayaan harus berupa angka.',
'principal.min' => 'Jumlah pembiayaan minimal Rp :min.',

// Tenor validation
'tenor.required' => 'Jangka waktu wajib dipilih.',
'tenor.in' => 'Jangka waktu tidak valid.',

// Admin config validation
'margin_rate.required' => 'Margin rate wajib diisi.',
'margin_rate.numeric' => 'Margin rate harus berupa angka.',
'margin_rate.min' => 'Margin rate harus lebih dari 0.',
'max_principal.gt' => 'Plafon maksimal harus lebih besar dari plafon minimal.',
```

### Edge Cases

1. **Division by zero**: Prevented by tenor validation (min:1)
2. **Negative values**: Prevented by validation rules
3. **Empty config**: Default values provided via seeder
4. **Cache miss**: Fallback to database query

## Testing Strategy

### Unit Tests

-   Test FinancingCalculatorService calculation methods
-   Test FinancingConfig model casts and accessors
-   Test validation rules

### Feature Tests

-   Test admin can access and update financing config
-   Test visitor can access simulation page
-   Test calculation returns correct results
-   Test validation errors are displayed

### Property-Based Tests

Using PHPUnit data providers to test properties:

```php
/**
 * @dataProvider calculationProvider
 */
public function test_calculation_formula_correctness($principal, $marginRate, $tenor): void
{
    $service = new FinancingCalculatorService();
    $result = $service->calculate($principal, $marginRate, $tenor);

    $expectedTotal = $principal + ($principal * $marginRate * ($tenor / 12));
    $expectedMonthly = $expectedTotal / $tenor;

    $this->assertEquals((int) round($expectedMonthly), $result['monthly_installment']);
}
```

### Test Configuration

-   PHPUnit for all tests
-   Use factories for test data generation
-   Use RefreshDatabase trait for isolation
-   Minimum 100 iterations for property tests via data providers
