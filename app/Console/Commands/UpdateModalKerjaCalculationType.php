<?php

namespace App\Console\Commands;

use App\Models\FinancingConfig;
use Illuminate\Console\Command;

class UpdateModalKerjaCalculationType extends Command
{
    protected $signature = 'financing:update-modal-kerja';
    protected $description = 'Update calculation type for Modal Kerja financing to profit_sharing';

    public function handle()
    {
        $config = FinancingConfig::where('type', 'musyarakah')
            ->orWhere('name', 'like', '%Modal Kerja%')
            ->first();

        if (!$config) {
            $this->error('Pembiayaan Modal Kerja tidak ditemukan.');
            return 1;
        }

        $oldType = $config->calculation_type;
        $config->calculation_type = 'profit_sharing';
        $config->save();

        $this->info("✓ Berhasil update: {$config->name}");
        $this->info("  Calculation Type: {$oldType} → profit_sharing");
        
        return 0;
    }
}
