<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasKelilingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class KasKelilingController extends Controller
{
    public function index(Request $request)
    {
        $query = KasKelilingSchedule::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('facility', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('schedule_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('schedule_date', '<=', $request->date_to);
        }

        $schedules = $query->orderBy('schedule_date', 'desc')
                          ->orderBy('start_time', 'asc')
                          ->paginate(20);

        return view('admin.kas-keliling.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.kas-keliling.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'schedule_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'facility' => 'nullable|string|max:1000',
                'pic_name' => 'nullable|string|max:255',
                'pic_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            // Auto-generate day_name
            $validated['day_name'] = Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
            $validated['is_active'] = $request->has('is_active');

            KasKelilingSchedule::create($validated);

            return redirect()->route('admin.kas-keliling.index')
                ->with('success', 'Jadwal kas keliling berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan jadwal kas keliling.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $kasKeliling = KasKelilingSchedule::findOrFail($id);
            return view('admin.kas-keliling.edit', compact('kasKeliling'));
        } catch (\Exception $e) {
            return redirect()->route('admin.kas-keliling.index')
                ->with('error', 'Jadwal kas keliling tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kasKeliling = KasKelilingSchedule::findOrFail($id);
            
            $validated = $request->validate([
                'schedule_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'facility' => 'nullable|string|max:1000',
                'pic_name' => 'nullable|string|max:255',
                'pic_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            // Auto-generate day_name
            $validated['day_name'] = Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
            $validated['is_active'] = $request->has('is_active');

            $kasKeliling->update($validated);

            return redirect()->route('admin.kas-keliling.index')
                ->with('success', 'Jadwal kas keliling berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal kas keliling.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kasKeliling = KasKelilingSchedule::findOrFail($id);
            $kasKeliling->delete();

            return redirect()->route('admin.kas-keliling.index')
                ->with('success', 'Jadwal kas keliling berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.kas-keliling.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jadwal kas keliling.');
        }
    }
}
