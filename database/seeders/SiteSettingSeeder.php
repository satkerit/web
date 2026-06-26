<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::updateOrCreate(
            [],
            [
                'hero_slider_delay' => 5000,
                'hero_slide_limit' => 5,
                'maintenance_mode' => false,
                'maintenance_message' => 'Website sedang dalam pemeliharaan untuk peningkatan layanan. Silakan kembali beberapa saat lagi.',
                'upload_max_filesize' => '100M',
                'post_max_size' => '100M',
                'max_execution_time' => 300,
                'max_input_time' => 300,
                'memory_limit' => '512M',
                'max_file_uploads' => 20,
                'report_keuangan_publikasi_title' => 'Laporan Keuangan Publikasi',
                'report_keuangan_publikasi_subtitle' => 'Laporan keuangan publikasi BPR Syariah',
                'report_tata_kelola_title' => 'Laporan Tata Kelola',
                'report_tata_kelola_subtitle' => 'Laporan tata kelola perusahaan',
                'report_tahunan_title' => 'Laporan Tahunan',
                'report_tahunan_subtitle' => 'Laporan tahunan BPR Syariah',
                'report_tahunan_berkelanjutan_title' => 'Laporan Tahunan Berkelanjutan',
                'report_tahunan_berkelanjutan_subtitle' => 'Laporan tahunan berkelanjutan BPR Syariah',
            ]
        );
    }
}
