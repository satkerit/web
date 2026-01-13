<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancingConfig;

class FinancingConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Default Murabahah config with 12% margin
        FinancingConfig::create([
            'type' => 'murabahah',
            'name' => 'Pembiayaan Murabahah',
            'margin_rate' => 0.12,
            'min_principal' => 5000000,
            'max_principal' => 500000000,
            'available_tenors' => [12, 24, 36, 48, 60],
            'is_active' => true,
        ]);

        // Default Musyarakah config with 10% margin
        FinancingConfig::create([
            'type' => 'musyarakah',
            'name' => 'Pembiayaan Musyarakah',
            'margin_rate' => 0.10,
            'min_principal' => 10000000,
            'max_principal' => 1000000000,
            'available_tenors' => [12, 24, 36, 48, 60],
            'is_active' => true,
        ]);
    }
}
