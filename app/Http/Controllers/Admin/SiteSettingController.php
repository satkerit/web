<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeAny(['settings.site']);
        $settings = SiteSetting::getSettings();
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorizeAny(['settings.site']);

        try {
            $validated = $request->validate([
                'hero_slider_delay' => 'nullable|integer|min:1000|max:10000',
                'hero_slide_limit' => 'nullable|integer|min:1|max:20',
                'maintenance_mode' => 'nullable|boolean',
                'maintenance_message' => 'nullable|string|max:500',
                'upload_max_filesize' => 'nullable|string|regex:/^\d+(K|M|G)?$/i',
                'post_max_size' => 'nullable|string|regex:/^\d+(K|M|G)?$/i',
                'max_execution_time' => 'nullable|integer|min:30|max:3600',
                'max_input_time' => 'nullable|integer|min:30|max:3600',
                'memory_limit' => 'nullable|string|regex:/^\d+(K|M|G)?$/i',
                'max_file_uploads' => 'nullable|integer|min:1|max:100',
            ], [
                'hero_slider_delay.min' => 'Delay slider minimal 1000ms',
                'hero_slider_delay.max' => 'Delay slider maksimal 10000ms',
                'hero_slide_limit.min' => 'Jumlah slide hero minimal 1',
                'hero_slide_limit.max' => 'Jumlah slide hero maksimal 20',
                'maintenance_message.max' => 'Pesan maintenance maksimal 500 karakter',
                'upload_max_filesize.regex' => 'Format ukuran file tidak valid (contoh: 100M, 2G)',
                'post_max_size.regex' => 'Format ukuran post tidak valid (contoh: 100M, 2G)',
                'max_execution_time.min' => 'Waktu eksekusi minimal 30 detik',
                'max_execution_time.max' => 'Waktu eksekusi maksimal 3600 detik',
                'max_input_time.min' => 'Waktu input minimal 30 detik',
                'max_input_time.max' => 'Waktu input maksimal 3600 detik',
                'memory_limit.regex' => 'Format batas memori tidak valid (contoh: 512M, 2G)',
                'max_file_uploads.min' => 'Jumlah file upload minimal 1',
                'max_file_uploads.max' => 'Jumlah file upload maksimal 100',
            ]);

            $settings = SiteSetting::getSettings();
            $settings->update($validated);

            // Clear relevant caches
            Cache::forget('site_settings');
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget("hero_slides_{$i}");
            }

            return redirect()->route('admin.site-settings.index')
                ->with('success', 'Pengaturan website berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.site-settings.index')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.site-settings.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateHeroSlideLimit(Request $request)
    {
        $this->authorizeAny(['settings.site']);

        try {
            $validated = $request->validate([
                'hero_slide_limit' => 'required|integer|min:1|max:20',
            ], [
                'hero_slide_limit.required' => 'Jumlah slide hero wajib diisi',
                'hero_slide_limit.min' => 'Jumlah slide hero minimal 1',
                'hero_slide_limit.max' => 'Jumlah slide hero maksimal 20',
            ]);

            $settings = SiteSetting::getSettings();
            $settings->update($validated);

            // Clear hero slides cache
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget("hero_slides_{$i}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Jumlah slide hero berhasil diperbarui',
                'data' => ['hero_slide_limit' => $validated['hero_slide_limit']]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
