<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUsSetting;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhyChooseUsSettingController extends Controller
{
    use HandlesImageUpload;

    public function edit()
    {
        $setting = WhyChooseUsSetting::getSettings();
        return view('admin.why-choose-us.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'section_subtitle' => 'nullable|string',
            'section_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'badge_text' => 'nullable|string|max:255',
            'badge_icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        try {
            $setting = WhyChooseUsSetting::getSettings();

            // Handle section image upload
            if ($request->hasFile('section_image')) {
                if ($setting->section_image) {
                    Storage::disk('public')->delete($setting->section_image);
                }
                $validated['section_image'] = $this->storeOptimizedImage(
                    $request->file('section_image'),
                    'why-choose-us/section'
                );
            }

            // Handle badge icon upload
            if ($request->hasFile('badge_icon')) {
                if ($setting->badge_icon) {
                    Storage::disk('public')->delete($setting->badge_icon);
                }
                $validated['badge_icon'] = $this->storeOptimizedImage(
                    $request->file('badge_icon'),
                    'why-choose-us/badges'
                );
            }

            $setting->update($validated);

            return redirect()->route('admin.why-choose-us-settings.edit')
                ->with('success', 'Pengaturan section berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating Why Choose Us settings: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui pengaturan. Silakan coba lagi.');
        }
    }
}
