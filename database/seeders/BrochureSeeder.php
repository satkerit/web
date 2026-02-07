<?php

namespace Database\Seeders;

use App\Models\Brochure;
use Illuminate\Database\Seeder;

class BrochureSeeder extends Seeder
{
    public function run(): void
    {
        $brochures = [
            [
                'filename' => 'brosur-tabungan-wadiah-2024.pdf',
                'original_name' => 'Brosur Tabungan Wadiah 2024.pdf',
                'file_path' => 'brochures/brosur-tabungan-wadiah-2024.pdf',
                'file_size' => 1024000, // 1MB
                'uploaded_by' => 1,
            ],
            [
                'filename' => 'brosur-deposito-mudharabah-2024.pdf',
                'original_name' => 'Brosur Deposito Mudharabah 2024.pdf',
                'file_path' => 'brochures/brosur-deposito-mudharabah-2024.pdf',
                'file_size' => 2048000, // 2MB
                'uploaded_by' => 1,
            ],
            [
                'filename' => 'brosur-pembiayaan-murabahah-2024.pdf',
                'original_name' => 'Brosur Pembiayaan Murabahah 2024.pdf',
                'file_path' => 'brochures/brosur-pembiayaan-murabahah-2024.pdf',
                'file_size' => 1536000, // 1.5MB
                'uploaded_by' => 1,
            ],
        ];

        foreach ($brochures as $brochure) {
            Brochure::updateOrCreate(
                ['filename' => $brochure['filename']],
                $brochure
            );
        }
    }
}
