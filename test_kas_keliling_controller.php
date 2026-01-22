<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\KasKelilingSchedule;
use App\Models\KasKeliling;

echo "Testing Kas Keliling Controller Logic\n";
echo "======================================\n\n";

$today = now()->startOfDay();
$endDate = now()->addDays(4)->endOfDay();

echo "Date Range:\n";
echo "Today: " . $today->toDateString() . "\n";
echo "End Date: " . $endDate->toDateString() . "\n\n";

$schedules = KasKelilingSchedule::with('kasKeliling')
    ->where('is_active', true)
    ->whereRaw('DATE(schedule_date) BETWEEN ? AND ?', [
        $today->toDateString(), 
        $endDate->toDateString()
    ])
    ->whereHas('kasKeliling', function($query) {
        $query->where('is_active', true);
    })
    ->orderByRaw('DATE(schedule_date)')
    ->orderBy('start_time')
    ->get();

echo "Total Schedules Found: " . $schedules->count() . "\n\n";

if ($schedules->count() > 0) {
    echo "Schedules:\n";
    foreach ($schedules as $schedule) {
        $dateStr = \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d');
        echo "- {$dateStr} ({$schedule->day_name}) - {$schedule->kasKeliling->area_name} at {$schedule->location}\n";
    }
    
    echo "\n\nGrouped by Date:\n";
    $grouped = $schedules->groupBy(function($schedule) {
        return \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d');
    });
    
    foreach ($grouped as $date => $dateSchedules) {
        echo "Date: {$date} - Count: " . $dateSchedules->count() . "\n";
    }
} else {
    echo "❌ No schedules found!\n";
    echo "\nDebugging:\n";
    echo "Total schedules in DB: " . KasKelilingSchedule::count() . "\n";
    echo "Active schedules: " . KasKelilingSchedule::where('is_active', true)->count() . "\n";
    
    $allSchedules = KasKelilingSchedule::all();
    echo "\nAll schedule dates:\n";
    foreach ($allSchedules as $s) {
        echo "- " . $s->schedule_date . " (raw)\n";
    }
}
