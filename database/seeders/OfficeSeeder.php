<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Data diambil dari website resmi bprsbabel.id
     * Sumber: https://bprsbabel.id/tentang-kami/lokasi-kantor
     */
    public function run(): void
    {
        $offices = [
            // Kantor Pusat
            [
                'name' => 'Kantor Pusat & Operasional',
                'type' => 'pusat',
                'address' => 'TJ TOWER, Jl. Kampung Melayu, Bukit Merapin 33172',
                'description' => 'Kantor pusat PT. BPRS Bangka Belitung yang melayani berbagai kebutuhan perbankan syariah. Dilengkapi dengan fasilitas modern dan tim profesional yang siap membantu nasabah.',
                'phone' => '0717-9103567',
                'email' => 'customercare@bprsbabel.id',
                'latitude' => -2.1316,
                'longitude' => 106.1169,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 16:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            // Kantor Cabang
            [
                'name' => 'Cabang Sungailiat',
                'type' => 'cabang',
                'address' => 'Jl. Jenderal Sudirman, Sungai Liat 33211, Kabupaten Bangka',
                'description' => 'Kantor cabang yang melayani wilayah Sungailiat dan sekitarnya di Kabupaten Bangka.',
                'phone' => '0717-95946',
                'email' => 'sungailiat@bprsbabel.id',
                'latitude' => -1.8589,
                'longitude' => 106.1297,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cabang Mentok',
                'type' => 'cabang',
                'address' => 'Jl. Jendral Sudirman Taman Lokomobil, Kel. Sungai Daeng, Mentok, Bangka Barat',
                'description' => 'Kantor cabang yang melayani wilayah Mentok dan sekitarnya di Kabupaten Bangka Barat.',
                'phone' => '0716-21212',
                'email' => 'mentok@bprsbabel.id',
                'latitude' => -2.0667,
                'longitude' => 105.1500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cabang Koba',
                'type' => 'cabang',
                'address' => 'Jl. Soekarno Hatta II, Kel. Berok, Kec. Koba, Kabupaten Bangka Tengah 33681',
                'description' => 'Kantor cabang yang melayani wilayah Koba dan sekitarnya di Kabupaten Bangka Tengah.',
                'phone' => '0718-61588',
                'email' => 'koba@bprsbabel.id',
                'latitude' => -2.4833,
                'longitude' => 106.4500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cabang Toboali',
                'type' => 'cabang',
                'address' => 'Jl. Jend. Sudirman No. 36, Kec. Toboali, Kabupaten Bangka Selatan 33783',
                'description' => 'Kantor cabang yang melayani wilayah Toboali dan sekitarnya di Kabupaten Bangka Selatan.',
                'phone' => '0718-42094',
                'email' => 'toboali@bprsbabel.id',
                'latitude' => -2.9833,
                'longitude' => 106.4500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cabang Tanjung Pandan',
                'type' => 'cabang',
                'address' => 'Jl. Diponegoro No. 19, Kelurahan Paal Satu, Kecamatan Tanjung Pandan, Belitung 33414',
                'description' => 'Kantor cabang yang melayani wilayah Tanjung Pandan dan sekitarnya di Kabupaten Belitung.',
                'phone' => '0719-22520',
                'email' => 'tanjungpandan@bprsbabel.id',
                'latitude' => -2.7500,
                'longitude' => 107.6500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cabang Manggar',
                'type' => 'cabang',
                'address' => 'Jl. Jend. Sudirman, Belitung Timur 33512',
                'description' => 'Kantor cabang yang melayani wilayah Manggar dan sekitarnya di Kabupaten Belitung Timur.',
                'phone' => '0719-92147',
                'email' => 'manggar@bprsbabel.id',
                'latitude' => -2.8833,
                'longitude' => 108.0500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:00 - 15:30 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            // Kantor Kas
            [
                'name' => 'Kas BTC',
                'type' => 'kas',
                'address' => 'Jl. Perniagaan Blok B1-B2 BTC Pangkalpinang',
                'description' => 'Kantor kas yang melayani transaksi harian nasabah di area BTC Pangkalpinang.',
                'phone' => '0717-423087',
                'email' => 'btc@bprsbabel.id',
                'latitude' => -2.1316,
                'longitude' => 106.1169,
                'operational_hours' => [
                    'Senin - Jumat' => '08:30 - 15:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Kas A. Yani',
                'type' => 'kas',
                'address' => 'Jl. A. Yani Dalam, Pangkalpinang',
                'description' => 'Kantor kas yang melayani transaksi harian nasabah di area Jl. A. Yani Pangkalpinang.',
                'phone' => '0717-438726',
                'email' => 'ayani@bprsbabel.id',
                'latitude' => -2.1316,
                'longitude' => 106.1169,
                'operational_hours' => [
                    'Senin - Jumat' => '08:30 - 15:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Kas Belinyu',
                'type' => 'kas',
                'address' => 'Jl. Sriwijaya (Terminal Lama Belinyu)',
                'description' => 'Kantor kas yang melayani transaksi harian nasabah di wilayah Belinyu.',
                'phone' => '0715-321004',
                'email' => 'belinyu@bprsbabel.id',
                'latitude' => -1.6333,
                'longitude' => 105.7500,
                'operational_hours' => [
                    'Senin - Jumat' => '08:30 - 15:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Kas Puding Besar',
                'type' => 'kas',
                'address' => 'Jl. Sungailiat Dusun II Puding Besar, Kabupaten Bangka',
                'description' => 'Kantor kas yang melayani transaksi harian nasabah di wilayah Puding Besar.',
                'phone' => '0717-8074567',
                'email' => 'pudingbesar@bprsbabel.id',
                'latitude' => -2.0000,
                'longitude' => 106.0000,
                'operational_hours' => [
                    'Senin - Jumat' => '08:30 - 15:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Kas Parit Tiga',
                'type' => 'kas',
                'address' => 'Jl. Pasar Ikan 33, Puput, Kec. Parittiga, Kabupaten Bangka Barat 33362',
                'description' => 'Kantor kas yang melayani transaksi harian nasabah di wilayah Parit Tiga.',
                'phone' => '0715-351111',
                'email' => 'parittiga@bprsbabel.id',
                'latitude' => -1.9500,
                'longitude' => 105.3000,
                'operational_hours' => [
                    'Senin - Jumat' => '08:30 - 15:00 WIB',
                    'Sabtu' => 'Tutup',
                    'Minggu' => 'Tutup'
                ],
                'is_active' => true
            ]
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(
                ['name' => $office['name']],
                $office
            );
        }
    }
}
