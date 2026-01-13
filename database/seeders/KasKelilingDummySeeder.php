<?php

namespace Database\Seeders;

use App\Models\KasKeliling;
use App\Models\KasKelilingSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KasKelilingDummySeeder extends Seeder
{
    public function run(): void
    {
        // Data kas keliling
        $kasKelilingData = [
            [
                'area_name' => 'Pasar Pagi Pangkalpinang',
                'contact_person' => 'Budi Santoso',
                'contact_phone' => '0812-3456-7890',
                'schedules' => [
                    [
                        'day' => 'Senin',
                        'location' => 'Depan Pasar Pagi Blok A',
                        'start' => '07:00',
                        'end' => '11:00',
                        'route' => ['Pasar Pagi', 'Jl. Sudirman', 'Komplek Pertokoan A'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer', 'Pembayaran']
                    ],
                    [
                        'day' => 'Rabu',
                        'location' => 'Depan Pasar Pagi Blok B',
                        'start' => '07:00',
                        'end' => '11:00',
                        'route' => ['Pasar Pagi', 'Jl. Merdeka', 'Komplek Pertokoan B'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer']
                    ],
                    [
                        'day' => 'Jumat',
                        'location' => 'Depan Pasar Pagi Blok C',
                        'start' => '07:00',
                        'end' => '10:00',
                        'route' => ['Pasar Pagi', 'Jl. Ahmad Yani'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai']
                    ],
                ]
            ],
            [
                'area_name' => 'Pasar Sungailiat',
                'contact_person' => 'Ahmad Hidayat',
                'contact_phone' => '0813-4567-8901',
                'schedules' => [
                    [
                        'day' => 'Selasa',
                        'location' => 'Halaman Pasar Sungailiat',
                        'start' => '08:00',
                        'end' => '12:00',
                        'route' => ['Pasar Sungailiat', 'Terminal Sungailiat', 'Perumahan Griya Indah'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Pembayaran Listrik', 'Pembayaran PDAM']
                    ],
                    [
                        'day' => 'Kamis',
                        'location' => 'Terminal Sungailiat',
                        'start' => '08:00',
                        'end' => '12:00',
                        'route' => ['Terminal Sungailiat', 'Pasar Sungailiat'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer']
                    ],
                    [
                        'day' => 'Sabtu',
                        'location' => 'Perumahan Griya Indah',
                        'start' => '08:00',
                        'end' => '11:00',
                        'route' => ['Perumahan Griya Indah', 'Komplek Perumahan'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai']
                    ],
                ]
            ],
            [
                'area_name' => 'Kawasan Industri Belinyu',
                'contact_person' => 'Siti Rahayu',
                'contact_phone' => '0814-5678-9012',
                'schedules' => [
                    [
                        'day' => 'Senin',
                        'location' => 'Gerbang Kawasan Industri',
                        'start' => '09:00',
                        'end' => '14:00',
                        'route' => ['Kawasan Industri', 'Pabrik A', 'Pabrik B', 'Kantor Kecamatan'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer', 'Pembukaan Rekening']
                    ],
                    [
                        'day' => 'Rabu',
                        'location' => 'Pasar Belinyu',
                        'start' => '07:30',
                        'end' => '11:30',
                        'route' => ['Pasar Belinyu', 'Jl. Raya Belinyu'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Pembayaran']
                    ],
                    [
                        'day' => 'Jumat',
                        'location' => 'Kantor Kecamatan Belinyu',
                        'start' => '09:00',
                        'end' => '13:00',
                        'route' => ['Kantor Kecamatan', 'Kawasan Industri'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer']
                    ],
                ]
            ],
            [
                'area_name' => 'Toboali & Sekitarnya',
                'contact_person' => 'Rudi Hartono',
                'contact_phone' => '0815-6789-0123',
                'schedules' => [
                    [
                        'day' => 'Selasa',
                        'location' => 'Pasar Toboali',
                        'start' => '07:00',
                        'end' => '11:00',
                        'route' => ['Pasar Toboali', 'Pelabuhan Toboali', 'Desa Tukak'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Angsuran Pembiayaan']
                    ],
                    [
                        'day' => 'Kamis',
                        'location' => 'Pelabuhan Toboali',
                        'start' => '08:00',
                        'end' => '12:00',
                        'route' => ['Pelabuhan Toboali', 'Pasar Toboali'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai']
                    ],
                ]
            ],
            [
                'area_name' => 'Manggar - Belitung Timur',
                'contact_person' => 'Dewi Lestari',
                'contact_phone' => '0816-7890-1234',
                'schedules' => [
                    [
                        'day' => 'Senin',
                        'location' => 'Pasar Manggar',
                        'start' => '08:00',
                        'end' => '12:00',
                        'route' => ['Pasar Manggar', 'Jl. Raya Manggar', 'Kecamatan Gantung'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer', 'Pembayaran']
                    ],
                    [
                        'day' => 'Rabu',
                        'location' => 'Kecamatan Gantung',
                        'start' => '09:00',
                        'end' => '13:00',
                        'route' => ['Kecamatan Gantung', 'Pasar Manggar'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai', 'Transfer']
                    ],
                    [
                        'day' => 'Sabtu',
                        'location' => 'Area Pantai Burung Mandi',
                        'start' => '08:00',
                        'end' => '11:00',
                        'route' => ['Pantai Burung Mandi', 'Area Wisata'],
                        'services' => ['Setoran Tunai', 'Penarikan Tunai']
                    ],
                ]
            ],
        ];

        // Mapping hari ke angka
        $dayMapping = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 0,
        ];

        foreach ($kasKelilingData as $data) {
            // Buat kas keliling
            $kasKeliling = KasKeliling::create([
                'area_name' => $data['area_name'],
                'contact_person' => $data['contact_person'],
                'contact_phone' => $data['contact_phone'],
                'is_active' => true,
            ]);

            // Generate jadwal untuk Januari 2026
            $startDate = Carbon::create(2026, 1, 1);
            $endDate = Carbon::create(2026, 1, 31);

            foreach ($data['schedules'] as $schedule) {
                $dayNumber = $dayMapping[$schedule['day']];
                $currentDate = $startDate->copy();

                // Cari hari pertama yang sesuai di bulan Januari
                while ($currentDate->dayOfWeek !== $dayNumber) {
                    $currentDate->addDay();
                }

                // Generate semua tanggal untuk hari tersebut di bulan Januari
                while ($currentDate->lte($endDate)) {
                    KasKelilingSchedule::create([
                        'kas_keliling_id' => $kasKeliling->id,
                        'schedule_date' => $currentDate->toDateString(),
                        'day_name' => $schedule['day'],
                        'start_time' => $schedule['start'],
                        'end_time' => $schedule['end'],
                        'location' => $schedule['location'],
                        'route' => $schedule['route'],
                        'services_offered' => $schedule['services'],
                        'notes' => null,
                        'is_active' => true,
                    ]);

                    $currentDate->addWeek();
                }
            }
        }

        $this->command->info('Data dummy kas keliling untuk Januari 2026 berhasil dibuat!');
    }
}
