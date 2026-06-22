<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\StoreReportRequest;
use App\Http\Requests\Admin\Report\UpdateReportRequest;
use App\Models\Report;
use App\Services\CacheService;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('reports.view');

        $query = Report::latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $reports = $query->paginate(15)->withQueryString();
        $years = Report::distinct()->pluck('year')->sort()->reverse();

        return view('admin.reports.index', compact('reports', 'years'));
    }

    public function create()
    {
        $this->authorizeCreate('reports.create');

        return view('admin.reports.form');
    }

    public function store(StoreReportRequest $request)
    {
        $this->authorizeCreate('reports.create');
        $validated = $request->validated();

        try {
            $validated['is_published'] = $request->boolean('is_published');

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $validated['file_path'] = $file->store('reports', 'public');
                $validated['file_size'] = $file->getSize();

                if (!$validated['file_path']) {
                    return back()->withInput()->with('error', 'Gagal menyimpan file. Periksa permission folder storage.');
                }
            }

            if ($validated['posting_mode'] === 'auto') {
                $validated['scheduled_at'] = null;
            }

            $validated['posted_at'] = $request->published_date;
            unset($validated['published_date']);
            unset($validated['file']);

            Report::create($validated);

            return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to create report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except('file')
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan laporan. Silakan coba lagi.');
        }
    }

    public function edit(Report $report)
    {
        $this->authorizeEdit('reports.edit');

        return view('admin.reports.form', compact('report'));
    }

    public function show(Report $report)
    {
        $this->authorizeView('reports.view');

        return redirect()->route('admin.reports.edit', $report);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $this->authorizeEdit('reports.edit');
        $validated = $request->validated();

        try {
            $validated['is_published'] = $request->boolean('is_published');

            if ($request->hasFile('file')) {
                if ($report->file_path) {
                    Storage::disk('public')->delete($report->file_path);
                }
                $file = $request->file('file');
                $validated['file_path'] = $file->store('reports', 'public');
                $validated['file_size'] = $file->getSize();

                if (!$validated['file_path']) {
                    return back()->withInput()->with('error', 'Gagal menyimpan file. Periksa permission folder storage.');
                }
            }

            if ($validated['posting_mode'] === 'auto') {
                $validated['scheduled_at'] = null;
            }

            $validated['posted_at'] = $request->published_date;
            unset($validated['published_date']);
            unset($validated['file']);

            $report->update($validated);

            return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update report', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except('file')
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui laporan. Silakan coba lagi.');
        }
    }

    public function destroy(Report $report)
    {
        $this->authorizeDelete('reports.delete');

        try {
            if ($report->file_path) {
                Storage::disk('public')->delete($report->file_path);
            }

            $report->delete();

            return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete report', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.reports.index')->with('error', 'Gagal menghapus laporan. Silakan coba lagi.');
        }
    }

    public function clearAllCaches()
    {
        $this->authorizeView('reports.view');

        $results = [];

        try {
            Artisan::call('cache:clear');
            $results[] = 'cache:clear OK';

            if (class_exists('\Spatie\ResponseCache\Facades\ResponseCache')) {
                \Spatie\ResponseCache\Facades\ResponseCache::clear();
                $results[] = 'responsecache:clear OK';
            }

            Artisan::call('view:clear');
            $results[] = 'view:clear OK';

            Artisan::call('config:clear');
            $results[] = 'config:clear OK';

            Artisan::call('route:clear');
            $results[] = 'route:clear OK';

            CacheService::clearReportCache();
            $results[] = 'Report cache cleared OK';
        } catch (\Exception $e) {
            $results[] = 'Error: ' . $e->getMessage();
            Log::error('Failed to clear caches', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return redirect()->route('admin.reports.index')->with('success', 'Cache cleared: ' . implode(', ', $results));
    }
}
