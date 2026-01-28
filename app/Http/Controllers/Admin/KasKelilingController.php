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

            // Check for duplicate schedule at same location and time
            $existingSchedule = KasKelilingSchedule::where('schedule_date', $validated['schedule_date'])
                ->where('location', $validated['location'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                          ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                          });
                })
                ->exists();

            if ($existingSchedule) {
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
            $existingSchedule = KasKelilingSchedule::where('id', '!=', $id)
                ->where('schedule_date', $validated['schedule_date'])
                ->where('location', $validated['location'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                          ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                          });
                })
                ->exists();

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

    public function export(Request $request)
    {
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
