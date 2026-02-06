<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorStatController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('audit.visitors');

        $period = $request->get('period', '7days');

        // Calculate date range based on period
        $dateRange = $this->getDateRange($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Basic stats
        $stats = [
            'total_visits' => VisitorLog::whereBetween('created_at', [$startDate, $endDate])->count(),
            'unique_visitors' => VisitorLog::whereBetween('created_at', [$startDate, $endDate])->distinct('ip_address')->count('ip_address'),
            'today_visits' => VisitorLog::today()->count(),
            'today_unique' => VisitorLog::today()->distinct('ip_address')->count('ip_address'),
        ];

        // Visits per day for chart
        $visitsPerDay = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'), DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top pages
        $topPages = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->select('url', DB::raw('COUNT(*) as visits'))
            ->groupBy('url')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        // Browser stats
        $browsers = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('browser')
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Device stats
        $devices = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        // Platform stats
        $platforms = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('platform')
            ->select('platform', DB::raw('COUNT(*) as total'))
            ->groupBy('platform')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Country stats
        $countries = VisitorLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country')
            ->select('country', 'country_code', DB::raw('COUNT(*) as total'))
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Recent visitors
        $recentVisitors = VisitorLog::latest()
            ->limit(20)
            ->get();

        return view('admin.visitor-stats.index', compact(
            'stats',
            'visitsPerDay',
            'topPages',
            'browsers',
            'devices',
            'platforms',
            'countries',
            'recentVisitors',
            'period',
            'startDate',
            'endDate'
        ));
    }

    private function getDateRange(string $period): array
    {
        return match ($period) {
            'today' => ['start' => now()->startOfDay(), 'end' => now()->endOfDay()],
            '7days' => ['start' => now()->subDays(6)->startOfDay(), 'end' => now()->endOfDay()],
            '30days' => ['start' => now()->subDays(29)->startOfDay(), 'end' => now()->endOfDay()],
            '90days' => ['start' => now()->subDays(89)->startOfDay(), 'end' => now()->endOfDay()],
            'this_month' => ['start' => now()->startOfMonth(), 'end' => now()->endOfDay()],
            'last_month' => ['start' => now()->subMonth()->startOfMonth(), 'end' => now()->subMonth()->endOfMonth()],
            default => ['start' => now()->subDays(6)->startOfDay(), 'end' => now()->endOfDay()],
        };
    }
}
