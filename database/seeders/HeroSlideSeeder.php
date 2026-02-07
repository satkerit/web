<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::updateOrCreate(
            ['title' => 'Selamat Datang di BPR Syariah'],
            [
                'subtitle' => 'Solusi Keuangan Syariah Terpercaya untuk Kemajuan Bersama',
                'link_url' => '/tentang-kami/perusahaan',
                'link_text' => 'Pelajari Lebih Lanjut',
                'is_active' => true,
                'order_position' => 1
            ]
        );

        HeroSlide::updateOrCreate(
            ['title' => 'Produk Simpanan Syariah'],
            [
                'subtitle' => 'Investasi yang Aman dan Menguntungkan Sesuai Prinsip Syariah',
                'link_url' => '/produk-layanan/simpanan-syariah',
                'link_text' => 'Lihat Produk',
                'is_active' => true,
                'order_position' => 2
            ]
        );

        HeroSlide::updateOrCreate(
            ['title' => 'Pembiayaan Syariah'],
            [
                'subtitle' => 'Wujudkan Impian Anda dengan Pembiayaan Syariah yang Mudah dan Terpercaya',
                'link_url' => '/produk-layanan/pembiayaan-syariah',
                'link_text' => 'Ajukan Sekarang',
                'is_active' => true,
                'order_position' => 3
            ]
        );
    }
}
