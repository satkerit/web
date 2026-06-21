<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasKelilingSchedule;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class KasKelilingController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('kas_keliling.view');
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
        $this->authorizeCreate('kas_keliling.manage');
        return view('admin.kas-keliling.create');
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('kas_keliling.manage');
        try {
            $validated = $request->validate([
                'schedule_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'facility' => 'nullable|string|max:1000',
                'pic_name' => 'nullable|string|max:255',
                'pic_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ], [
                'schedule_date.after_or_equal' => 'Tanggal jadwal tidak boleh kurang dari hari ini.',
                'pic_phone.regex' => 'Format nomor telepon tidak valid.',
                'end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.'
            ]);

            if ($this->scheduleOverlapExists($validated)) {
                return redirect()->back()
                    ->with('error', 'Sudah ada jadwal kas keliling di lokasi dan waktu yang sama.')
                    ->withInput();
            }

            // Auto-generate day_name
            $validated['day_name'] = Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
            $validated['is_active'] = $request->has('is_active');

            KasKelilingSchedule::create($validated);

            return redirect()->route('admin.kas-keliling.index')
                ->with('success', 'Jadwal kas keliling berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan jadwal kas keliling: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $this->authorizeEdit('kas_keliling.manage');
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
        $this->authorizeEdit('kas_keliling.manage');
        try {
            $kasKeliling = KasKelilingSchedule::findOrFail($id);
            
            $validated = $request->validate([
                'schedule_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'location' => 'required|string|max:255',
                'facility' => 'nullable|string|max:1000',
                'pic_name' => 'nullable|string|max:255',
                'pic_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ], [
                'schedule_date.after_or_equal' => 'Tanggal jadwal tidak boleh kurang dari hari ini.',
                'pic_phone.regex' => 'Format nomor telepon tidak valid.',
                'end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.'
            ]);

            // Check for duplicate schedule at same location and time (excluding current record)
            $existingSchedule = $this->scheduleOverlapExists($validated, $id);

            if ($existingSchedule) {
                return redirect()->back()
                    ->with('error', 'Sudah ada jadwal kas keliling di lokasi dan waktu yang sama.')
                    ->withInput();
            }

            // Auto-generate day_name
            $validated['day_name'] = Carbon::parse($validated['schedule_date'])->locale('id')->dayName;
            $validated['is_active'] = $request->has('is_active');

            $kasKeliling->update($validated);

            return redirect()->route('admin.kas-keliling.index')
                ->with('success', 'Jadwal kas keliling berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal kas keliling: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $this->authorizeDelete('kas_keliling.manage');
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

    public function bulkDelete(Request $request)
    {
        $this->authorizeDelete('kas_keliling.manage');
        try {
            $ids = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:kas_keliling_schedules,id'
            ])['ids'];

            $count = KasKelilingSchedule::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$count} jadwal kas keliling"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal'
            ], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $this->authorizeEdit('kas_keliling.manage');
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:kas_keliling_schedules,id',
                'status' => 'required|boolean'
            ]);

            $count = KasKelilingSchedule::whereIn('id', $validated['ids'])
                ->update(['is_active' => $validated['status']]);

            $statusText = $validated['status'] ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "Berhasil {$statusText} {$count} jadwal kas keliling"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status jadwal'
            ], 500);
        }
    }

    protected function scheduleOverlapExists(array $data, ?int $excludeId = null): bool
    {
        $query = KasKelilingSchedule::where('schedule_date', $data['schedule_date'])
            ->where('location', $data['location']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->where(function ($q) use ($data) {
            $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                ->orWhere(function ($inner) use ($data) {
                    $inner->where('start_time', '<=', $data['start_time'])
                        ->where('end_time', '>=', $data['end_time']);
                });
        })->exists();
    }

    public function export(Request $request)
    {
        $this->authorizeView('kas_keliling.view');
        try {
            $query = KasKelilingSchedule::query();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('location', 'like', "%{$search}%")
                      ->orWhere('pic_name', 'like', "%{$search}%")
                      ->orWhere('facility', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            if ($request->filled('date_from')) {
                $query->where('schedule_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('schedule_date', '<=', $request->date_to);
            }

            $schedules = $query->orderBy('schedule_date', 'desc')->get();

            $filename = 'kas_keliling_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($schedules) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, [
                    'Tanggal',
                    'Hari',
                    'Jam Mulai',
                    'Jam Selesai',
                    'Lokasi',
                    'Fasilitas',
                    'PIC',
                    'Telepon PIC',
                    'Catatan',
                    'Status'
                ]);

                // Data
                foreach ($schedules as $schedule) {
                    fputcsv($file, [
                        $schedule->schedule_date->format('d/m/Y'),
                        $schedule->day_name,
                        $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : '',
                        $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : '',
                        $schedule->location,
                        $schedule->facility,
                        $schedule->pic_name,
                        $schedule->pic_phone,
                        $schedule->notes,
                        $schedule->is_active ? 'Aktif' : 'Tidak Aktif'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }
}
