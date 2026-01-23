<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KasKeliling;
use App\Models\KasKelilingSchedule;
use Carbon\Carbon;

class DebugKasKeliling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kas-keliling:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug kas keliling data and display schedule information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Debugging Kas Keliling Data...');
        $this->newLine();

        // Environment Info
        $this->info('📊 Environment Information:');
        $this->table(
            ['Key', 'Value'],
            [
                ['Environment', app()->environment()],
                ['Timezone', config('app.timezone')],
                ['Current Time', now()->toString()],
                ['Current Date', now()->toDateString()],
                ['Database', config('database.default')],
            ]
        );
        $this->newLine();

        // Check Kas Keliling Data
        $this->info('📋 Kas Keliling Master Data:');
        $kasKelilings = KasKeliling::all();
        
        if ($kasKelilings->isEmpty()) {
            $this->error('❌ No kas keliling data found!');
            $this->warn('💡 Run: php artisan db:seed --class=KasKelilingFullSeeder');
            $this->newLine();
        } else {
            $this->table(
                ['ID', 'Area Name', 'Active', 'Contact', 'Phone', 'Schedules Count'],
                $kasKelilings->map(function($kk) {
                    return [
                        $kk->id,
                        $kk->area_name,
                        $kk->is_active ? '✅ Yes' : '❌ No',
                        $kk->contact_person ?? '-',
                        $kk->contact_phone ?? '-',
                        $kk->schedules()->count(),
                    ];
                })
            );
            $this->newLine();
        }

        // Check Schedules
        $this->info('📅 Kas Keliling Schedules (All):');
        $allSchedules = KasKelilingSchedule::with('kasKeliling')->orderBy('schedule_date')->get();
        
        if ($allSchedules->isEmpty()) {
            $this->error('❌ No schedule data found!');
            $this->warn('💡 Run: php artisan db:seed --class=KasKelilingFullSeeder');
            $this->newLine();
        } else {
            $this->info("Total schedules: {$allSchedules->count()}");
            $this->table(
                ['ID', 'Date', 'Day', 'Time', 'Location', 'Area', 'Active'],
                $allSchedules->take(10)->map(function($s) {
                    return [
                        $s->id,
                        $s->schedule_date->format('Y-m-d'),
                        $s->day_name ?? '-',
                        ($s->start_time ? Carbon::parse($s->start_time)->format('H:i') : '-') . ' - ' . 
                        ($s->end_time ? Carbon::parse($s->end_time)->format('H:i') : '-'),
                        $s->location ?? '-',
                        $s->kasKeliling->area_name ?? 'N/A',
                        $s->is_active ? '✅' : '❌',
                    ];
                })
            );
            if ($allSchedules->count() > 10) {
                $this->info("... and " . ($allSchedules->count() - 10) . " more");
            }
            $this->newLine();
        }

        // Check Upcoming Schedules (Next 5 days)
        $this->info('🔜 Upcoming Schedules (Next 5 Days):');
        $today = now()->startOfDay();
        $endDate = now()->addDays(4)->endOfDay();
        
        $this->info("Date Range: {$today->toDateString()} to {$endDate->toDateString()}");
        $this->newLine();

        $upcomingSchedules = KasKelilingSchedule::with('kasKeliling')
            ->where('is_active', true)
            ->whereBetween('schedule_date', [
                $today->toDateString(), 
                $endDate->toDateString()
            ])
            ->whereHas('kasKeliling', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($upcomingSchedules->isEmpty()) {
            $this->error('❌ No upcoming schedules found!');
            $this->warn('💡 Possible reasons:');
            $this->warn('   1. No schedules in date range');
            $this->warn('   2. All schedules are inactive (is_active = 0)');
            $this->warn('   3. Parent kas_keliling is inactive');
            $this->newLine();
            
            // Debug: Check inactive schedules
            $inactiveSchedules = KasKelilingSchedule::with('kasKeliling')
                ->whereBetween('schedule_date', [
                    $today->toDateString(), 
                    $endDate->toDateString()
                ])
                ->where('is_active', false)
                ->count();
            
            if ($inactiveSchedules > 0) {
                $this->warn("   Found {$inactiveSchedules} inactive schedules in date range");
            }
            
            // Debug: Check schedules with inactive parent
            $schedulesWithInactiveParent = KasKelilingSchedule::with('kasKeliling')
                ->whereBetween('schedule_date', [
                    $today->toDateString(), 
                    $endDate->toDateString()
                ])
                ->whereHas('kasKeliling', function($query) {
                    $query->where('is_active', false);
                })
                ->count();
            
            if ($schedulesWithInactiveParent > 0) {
                $this->warn("   Found {$schedulesWithInactiveParent} schedules with inactive parent");
            }
            
        } else {
            $this->info("✅ Found {$upcomingSchedules->count()} upcoming schedules");
            $this->table(
                ['Date', 'Day', 'Time', 'Location', 'Area', 'Contact'],
                $upcomingSchedules->map(function($s) {
                    $isToday = $s->schedule_date->isToday();
                    $isTomorrow = $s->schedule_date->isTomorrow();
                    $badge = $isToday ? ' 🔥 TODAY' : ($isTomorrow ? ' ⭐ TOMORROW' : '');
                    
                    return [
                        $s->schedule_date->format('Y-m-d') . $badge,
                        $s->day_name ?? '-',
                        ($s->start_time ? Carbon::parse($s->start_time)->format('H:i') : '-') . ' - ' . 
                        ($s->end_time ? Carbon::parse($s->end_time)->format('H:i') : '-'),
                        $s->location ?? '-',
                        $s->kasKeliling->area_name ?? 'N/A',
                        $s->kasKeliling->contact_person ?? '-',
                    ];
                })
            );
            $this->newLine();
            
            // Group by date
            $grouped = $upcomingSchedules->groupBy(function($s) {
                return $s->schedule_date->format('Y-m-d');
            });
            
            $this->info('📊 Grouped by Date:');
            foreach ($grouped as $date => $schedules) {
                $this->line("  {$date}: {$schedules->count()} location(s)");
            }
        }
        
        $this->newLine();
        $this->info('✅ Debug completed!');
        
        return 0;
    }
}
