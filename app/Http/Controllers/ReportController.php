<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    private function getReports(Request $request, string $type, string $title, string $subtitle)
    {
        $year = $request->query('year');
        $page = $request->query('page', 1);
        $cacheKey = "reports_{$type}_{$year}_{$page}";

        $reports = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($type, $year) {
            $query = Report::where('type', $type)->published();
            if ($year) {
                $query->where('year', $year);
            }
            return $query->orderBy('year', 'desc')->orderBy('quarter', 'desc')->paginate(15);
        });

        return view('frontend.pages.reports.index', [
            'reports' => $reports->withQueryString(),
            'years' => CacheService::getReportYears($type),
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type,
        ]);
    }

    public function keuanganPublikasi(Request $request)
    {
        return $this->getReports($request, 'keuangan_publikasi', 'Laporan Keuangan Publikasi', 'Laporan keuangan publikasi BPR Syariah');
    }

    public function tataKelola(Request $request)
    {
        return $this->getReports($request, 'tata_kelola', 'Laporan Tata Kelola', 'Laporan tata kelola perusahaan');
    }

    public function tahunan(Request $request)
    {
        return $this->getReports($request, 'tahunan', 'Laporan Tahunan', 'Laporan tahunan BPR Syariah');
    }

    public function tahunanBerkelanjutan(Request $request)
    {
        return $this->getReports($request, 'tahunan_berkelanjutan', 'Laporan Tahunan Berkelanjutan', 'Laporan tahunan berkelanjutan BPR Syariah');
    }

    public function preview(int $id)
    {
        $report = Report::published()->findOrFail($id);

        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment preview count and clear cache
        $report->increment('preview_count');
        CacheService::clearReportCache();

        return response()->file(
            Storage::disk('public')->path($report->file_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    public function download(int $id)
    {
        $report = Report::published()->findOrFail($id);

        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment download count and clear cache
        $report->increment('download_count');
        CacheService::clearReportCache();

        return Storage::disk('public')->download($report->file_path, $report->title . '.pdf');
    }

    /**
     * Get hit counts for a report (AJAX endpoint)
     */
    public function getHitCounts(int $id)
    {
        $report = Report::published()->findOrFail($id);

        return response()->json([
            'preview_count' => $report->preview_count,
            'download_count' => $report->download_count,
        ]);
    }
}
