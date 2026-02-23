<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'hero_slider_delay' => 'required|integer|min:1000|max:20000',
                'hero_slide_limit' => 'required|integer|min:1|max:20',
                'maintenance_mode' => 'boolean',
                'maintenance_message' => 'nullable|string|max:1000',
                'maintenance_allowed_ips' => 'nullable|string',
                'maintenance_end_time' => 'nullable|date',
                'maintenance_pages' => 'nullable|array',
            ], [
                'hero_slider_delay.required' => 'Delay slider hero wajib diisi',
                'hero_slider_delay.min' => 'Delay slider hero minimal 1000ms',
                'hero_slider_delay.max' => 'Delay slider hero maksimal 20000ms',
                'hero_slide_limit.required' => 'Jumlah slide hero wajib diisi',
                'hero_slide_limit.min' => 'Jumlah slide hero minimal 1',
                'hero_slide_limit.max' => 'Jumlah slide hero maksimal 20',
            ]);

            $settings = SiteSetting::getSettings();
            $settings->update($validated);

            // Clear relevant caches
            Cache::forget('site_settings');
            Cache::forget('hero_slides_5'); // Clear default cache
            
            // Clear all possible hero slide caches
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget("hero_slides_{$i}");
            }

            return redirect()-&gt;route('admin.site-settings.index')
                -&gt;with('success', 'Pengaturan website berhasil diperbarui');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()-&gt;back()
                -&gt;withErrors($e-&gt;validator)
                -&gt;withInput();
        } catch (\Exception $e) {
            return redirect()-&gt;back()
                -&gt;with('error', 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e-&gt;getMessage())
                -&gt;withInput();
        }
    }

    public function updateHeroSlideLimit(Request $request)
    {
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

            return response()-&gt;json([
                'success' => true,
                'message' => 'Jumlah slide hero berhasil diperbarui',
                'data' => ['hero_slide_limit' => $validated['hero_slide_limit']]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()-&gt;json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e-&gt;validator-&gt;errors()
            ], 422);
        } catch (\Exception $e) {
            return response()-&gt;json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e-&gt;getMessage()
            ], 500);
        }
    }
}