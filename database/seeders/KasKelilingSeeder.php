<?php

namespace Database\Seeders;

use App\Models\KasKeliling;
use App\Models\KasKelilingSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KasKelilingSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        KasKelilingSchedule::truncate();
        KasKeliling::truncate();
        Schema::enableForeignKeyConstraints();

        // Data Kas Keliling
        $kasKelilingData = [
            [
                'area_name' => 'Pasar Pagi Sungailiat',
                'contact_person' => 'Budi Santoso',
                'contact_phone' => '0812-3456-7890',
                'services_offered' => [
                    'Setoran Tabungan',
                    'Pembayaran Angsuran',
                    'Penarikan Tunai'
                ],
                'is_active' => true,
                'schedules' => [
                    [
                        'day' => 'Monday',
                        'start_time' => '08:00',
                        'end_time' => '12:00',
                        'location' => 'Depan Pasar Pagi',
                        'route' => ['Pasar Pagi', 'Toko Kelontong', 'Warung Makan'],
                        'services' => ['Setoran Tabungan', 'Pembayaran Angsuran'],
                        'notes' => 'Bawa buku tabungan'
                    ],
                    [
                        'day' => 'Thursday',
                        'start_time' => '08:00',
                        'end_time' => '12:00',
                        'location' => 'Depan Pasar Pagi',
                        'route' => ['Pasar Pagi', 'Toko Kelontong'],
                        'services' => ['Setoran Tabungan', 'Penarikan Tunai'],
                        'notes' => null
                    ]
                ]
            ],
            [
                'area_name' => 'Kelurahan Pemali',
                'contact_person' => 'Siti Aminah',
                'contact_phone' => '0813-9876-5432',
                'services_offered' => [
                    'Setoran Tabungan',
                    'Pembayaran Angsuran'
                ],
                'is_active' => true,
                'schedules' => [
                    [
                        'day' => 'Tuesday',
                        'start_time' => '09:00',
                        'end_time' => '13:00',
                        'location' => 'Kantor Kelurahan Pemali',
                        'route' => ['Kelurahan', 'RT 01', 'RT 02'],
                        'services' => ['Setoran Tabungan', 'Pembayaran Angsuran'],
                        'notes' => 'Setiap hari Selasa'
                    ],
                    [
                        'day' => 'Friday',
                        'start_time' => '09:00',
                        'end_time' => '13:00',
                        'location' => 'Kantor Kelurahan Pemali',
                        'route' => ['Kelurahan', 'RT 03', 'RT 04'],
                        'services' => ['Setoran Tabungan'],
                        'notes' => null
                    ]
                ]
            ],
            [
                'area_name' => 'Komplek Perumahan Griya Asri',
                'contact_person' => 'Ahmad Yani',
                'contact_phone' => '0821-5555-6666',
                'services_offered' => [
                    'Setoran Tabungan',
                    'Pembayaran Angsuran',
                    'Pembukaan Rekening'
                ],
                'is_active' => true,
                'schedules' => [
                    [
                        'day' => 'Wednesday',
                        'start_time' => '10:00',
                        'end_time' => '14:00',
                        'location' => 'Pos Satpam Komplek',
                        'route' => ['Blok A', 'Blok B', 'Blok C'],
                        'services' => ['Setoran Tabungan', 'Pembayaran Angsuran', 'Pembukaan Rekening'],
                        'notes' => 'Bawa KTP untuk pembukaan rekening'
                    ]
                ]
            ],
            [
                'area_name' => 'Pasar Belinyu',
                'contact_person' => 'Dewi Lestari',
                'contact_phone' => '0822-7777-8888',
                'services_offered' => [
                    'Setoran Tabungan',
                    'Penarikan Tunai'
                ],
                'is_active' => true,
                'schedules' => [
                    [
                        'day' => 'Monday',
                        'start_time' => '07:30',
                        'end_time' => '11:30',
                        'location' => 'Area Parkir Pasar Belinyu',
                        'route' => ['Pasar Belinyu', 'Toko Elektronik', 'Toko Bangunan'],
                        'services' => ['Setoran Tabungan', 'Penarikan Tunai'],
                        'notes' => 'Datang pagi untuk menghindari antrian'
                    ],
                    [
                        'day' => 'Friday',
                        'start_time' => '07:30',
                        'end_time' => '11:30',
                        'location' => 'Area Parkir Pasar Belinyu',
                        'route' => ['Pasar Belinyu', 'Toko Pakaian'],
                        'services' => ['Setoran Tabungan'],
                        'notes' => null
                    ]
                ]
            ],
            [
                'area_name' => 'Desa Riau',
                'contact_person' => null,
                'contact_phone' => null,
                'services_offered' => [
                    'Setoran Tabungan'
                ],
                'is_active' => true,
                'schedules' => [
                    [
                        'day' => 'Thursday',
                        'start_time' => '13:00',
                        'end_time' => '16:00',
                        'location' => 'Balai Desa Riau',
                        'route' => ['Balai Desa', 'Masjid', 'Sekolah'],
                        'services' => ['Setoran Tabungan'],
                        'notes' => 'Setiap hari Kamis sore'
                    ]
                ]
            ]
        ];

        foreach ($kasKelilingData as $data) {
            $schedules = $data['schedules'];
            unset($data['schedules']);

            $kasKeliling = KasKeliling::create($data);

            // Create schedules for next 4 weeks
            foreach ($schedules as $scheduleData) {
                $dayName = $scheduleData['day'];

                // Get next 4 occurrences of this day
                $startDate = Carbon::now()->startOfDay();

                for ($week = 0; $week < 4; $week++) {
                    // Find next occurrence of this day
                    if ($week == 0) {
                        // For first week, find next occurrence from today
                        $date = Carbon::now()->next($dayName);
                    } else {
                        // For subsequent weeks, add weeks
                        $date = Carbon::now()->next($dayName)->addWeeks($week);
                    }

                    // Skip if date is in the past
                    if ($date->isPast()) {
                        continue;
                    }

                    KasKelilingSchedule::create([
                        'kas_keliling_id' => $kasKeliling->id,
                        'schedule_date' => $date->format('Y-m-d'), // Format as date only
                        'day_name' => $date->locale('id')->dayName,
                        'start_time' => $scheduleData['start_time'],
                        'end_time' => $scheduleData['end_time'],
                        'location' => $scheduleData['location'],
                        'route' => $scheduleData['route'],
                        'services_offered' => $scheduleData['services'],
                        'notes' => $scheduleData['notes'],
                        'is_active' => true
                    ]);
                }
            }
        }

        $this->command->info('Kas Keliling data seeded successfully!');
        $this->command->info('Total Kas Keliling: ' . KasKeliling::count());
        $this->command->info('Total Schedules: ' . KasKelilingSchedule::count());
    }
}
