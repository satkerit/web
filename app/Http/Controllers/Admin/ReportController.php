<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $this->authorizeCreate('reports.create');
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:keuangan_publikasi,tata_kelola,tahunan,tahunan_berkelanjutan',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'quarter' => 'nullable|integer|min:1|max:4',
            'file' => 'required|file|mimes:pdf|max:51200',
            'description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'posting_mode' => 'required|in:auto,manual',
            'scheduled_at' => 'nullable|date',
            'published_date' => 'required|date',
        ];

        if ($request->posting_mode === 'manual') {
            $rules['scheduled_at'] = 'required|date';
        }

        $validated = $request->validate($rules);

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

            // Set posted_at dari published_date yang diinput user
            $validated['posted_at'] = $request->published_date;
            unset($validated['published_date']);

            unset($validated['file']);

            Report::create($validated);

            return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    public function update(Request $request, Report $report)
    {
        $this->authorizeEdit('reports.edit');
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:keuangan_publikasi,tata_kelola,tahunan,tahunan_berkelanjutan',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'quarter' => 'nullable|integer|min:1|max:4',
            'file' => 'nullable|file|mimes:pdf|max:51200',
            'description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'posting_mode' => 'required|in:auto,manual',
            'scheduled_at' => 'nullable|date',
            'published_date' => 'required|date',
        ];

        if ($request->posting_mode === 'manual') {
            $rules['scheduled_at'] = 'required|date';
        }

        $validated = $request->validate($rules);

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

            // Set posted_at dari published_date yang diinput user
            $validated['posted_at'] = $request->published_date;
            unset($validated['published_date']);

            unset($validated['file']);

            $report->update($validated);

            return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            return redirect()->route('admin.reports.index')->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}
