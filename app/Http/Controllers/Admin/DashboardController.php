<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        // Dashboard is accessible by all authenticated admin users
        // No specific permission check needed - middleware already ensures user is authenticated

        // Visitor stats for last 7 days
        $visitorStats = $this->getVisitorStats();

        return view('admin.dashboard', compact('visitorStats'));
    }

    private function getVisitorStats(): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Daily visits for chart
        $dailyVisits = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with zero
        $labels = [];
        $totalVisits = [];
        $uniqueVisitors = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->locale('id')->isoFormat('ddd');

            $labels[] = $dayName;
            $totalVisits[] = $dailyVisits->get($date)?->total ?? 0;
            $uniqueVisitors[] = $dailyVisits->get($date)?->unique_visitors ?? 0;
        }

        // Summary stats
        $todayVisits = VisitorLog::whereDate('created_at', today())->count();
        $todayUnique = VisitorLog::whereDate('created_at', today())->distinct('ip_address')->count('ip_address');
        $weekTotal = array_sum($totalVisits);
        $weekUnique = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count('ip_address');

        return [
            'labels' => $labels,
            'totalVisits' => $totalVisits,
            'uniqueVisitors' => $uniqueVisitors,
            'todayVisits' => $todayVisits,
            'todayUnique' => $todayUnique,
            'weekTotal' => $weekTotal,
            'weekUnique' => $weekUnique,
        ];
    }
}
