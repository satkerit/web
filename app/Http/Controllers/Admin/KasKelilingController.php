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
            'route' => 'nullable|array',
            'route.*' => 'nullable|string',
            'schedule' => 'nullable|array',
            'schedule.*' => 'nullable|string',
            'operational_hours' => 'nullable|array',
            'operational_hours.start' => 'nullable|date_format:H:i',
            'operational_hours.end' => 'nullable|date_format:H:i',
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Filter empty values from arrays
        if (isset($validated['route'])) {
            $validated['route'] = array_values(array_filter($validated['route']));
        }
        if (isset($validated['schedule'])) {
            $validated['schedule'] = array_values(array_filter($validated['schedule']));
        }
        if (isset($validated['services_offered'])) {
            $validated['services_offered'] = array_values(array_filter($validated['services_offered']));
        }

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
            'route' => 'nullable|array',
            'route.*' => 'nullable|string',
            'schedule' => 'nullable|array',
            'schedule.*' => 'nullable|string',
            'operational_hours' => 'nullable|array',
            'operational_hours.start' => 'nullable|date_format:H:i',
            'operational_hours.end' => 'nullable|date_format:H:i',
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Filter empty values from arrays
        if (isset($validated['route'])) {
            $validated['route'] = array_values(array_filter($validated['route']));
        }
        if (isset($validated['schedule'])) {
            $validated['schedule'] = array_values(array_filter($validated['schedule']));
        }
        if (isset($validated['services_offered'])) {
            $validated['services_offered'] = array_values(array_filter($validated['services_offered']));
        }

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
            'day_name' => 'nullable|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'route' => 'nullable|array',
            'route.*' => 'nullable|string',
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Filter empty values from arrays
        if (isset($validated['route'])) {
            $validated['route'] = array_values(array_filter($validated['route']));
        }
        if (isset($validated['services_offered'])) {
            $validated['services_offered'] = array_values(array_filter($validated['services_offered']));
        }

        $validated['kas_keliling_id'] = $kasKeliling->id;
        
        // Auto-generate day_name if not provided
        if (empty($validated['day_name'])) {
            $validated['day_name'] = \Carbon\Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
        }
        
        $validated['is_active'] = $request->has('is_active');

        KasKelilingSchedule::create($validated);

        return redirect()->route('admin.kas-keliling.schedules', $kasKeliling)
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function updateSchedule(Request $request, KasKeliling $kasKeliling, KasKelilingSchedule $schedule)
    {
        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'day_name' => 'nullable|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'route' => 'nullable|array',
            'route.*' => 'nullable|string',
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Filter empty values from arrays
        if (isset($validated['route'])) {
            $validated['route'] = array_values(array_filter($validated['route']));
        }
        if (isset($validated['services_offered'])) {
            $validated['services_offered'] = array_values(array_filter($validated['services_offered']));
        }

        // Auto-generate day_name if not provided
        if (empty($validated['day_name'])) {
            $validated['day_name'] = \Carbon\Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
        }
        
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
