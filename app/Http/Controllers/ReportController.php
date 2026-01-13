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
        $query = Report::where('type', $type)->published();

        if ($year = $request->query('year')) {
            $query->where('year', $year);
        }

        return view('frontend.pages.reports.index', [
            'reports' => $query->orderBy('year', 'desc')->orderBy('quarter', 'desc')->paginate(15),
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

        // Increment preview count
        $report->increment('preview_count');

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

        // Increment download count
        $report->increment('download_count');

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
