<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasKeliling;
use App\Models\KasKelilingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KasKelilingController extends Controller
{
    public function index(Request $request)
    {
        $query = KasKeliling::query()->with('schedules');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('area_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $kasKelilings = $query->latest()->paginate(15);

        return view('admin.kas-keliling.index', compact('kasKelilings'));
    }

    public function create()
    {
        return view('admin.kas-keliling.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'operational_hours' => 'nullable|array',
            'services_offered' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        KasKeliling::create($validated);

        return redirect()->route('admin.kas-keliling.index')

            ->with('success', 'Kas Keliling berhasil ditambahkan');
    }

    public function edit(KasKeliling $kasKeliling)
    {
        return view('admin.kas-keliling.edit', compact('kasKeliling'));
    }

    public function update(Request $request, KasKeliling $kasKeliling)
    {
        $validated = $request->validate([
            'area_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'operational_hours' => 'nullable|array',
            'services_offered' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $kasKeliling->update($validated);

        return redirect()->route('admin.kas-keliling.index')
            ->with('success', 'Kas Keliling berhasil diperbarui');
    }

    public function destroy(KasKeliling $kasKeliling)
    {
        $kasKeliling->delete();

        return redirect()->route('admin.kas-keliling.index')
            ->with('success', 'Kas Keliling berhasil dihapus');
    }

    // Schedule Management
    public function schedules(KasKeliling $kasKeliling)
    {
        $schedules = $kasKeliling->schedules()
            ->orderBy('schedule_date', 'desc')
            ->paginate(20);

        return view('admin.kas-keliling.schedules', compact('kasKeliling', 'schedules'));
    }

    public function storeSchedule(Request $request, KasKeliling $kasKeliling)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'route' => 'nullable|array',
            'services_offered' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['kas_keliling_id'] = $kasKeliling->id;
        $validated['day_name'] = \Carbon\Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
        $validated['is_active'] = $request->has('is_active');

        KasKelilingSchedule::create($validated);

        return redirect()->route('admin.kas-keliling.schedules', $kasKeliling)
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function updateSchedule(Request $request, KasKeliling $kasKeliling, KasKelilingSchedule $schedule)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'route' => 'nullable|array',
            'services_offered' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['day_name'] = \Carbon\Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
        $validated['is_active'] = $request->has('is_active');

        $schedule->update($validated);

        return redirect()->route('admin.kas-keliling.schedules', $kasKeliling)
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroySchedule(KasKeliling $kasKeliling, KasKelilingSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.kas-keliling.schedules', $kasKeliling)
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
