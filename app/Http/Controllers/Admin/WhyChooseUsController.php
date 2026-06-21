<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs;
use App\Models\WhyChooseUsSetting;
use App\Services\CacheService;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhyChooseUsController extends Controller
{
    use AuthorizesAdminActions;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorizeView('why_choose_us.view');
        $items = WhyChooseUs::orderBy('sort_order')->get();
        return view('admin.why-choose-us.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeCreate('why_choose_us.manage');
        return view('admin.why-choose-us.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeCreate('why_choose_us.manage');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,svg,webp',
            'color_theme' => 'required|string|in:primary,emerald,blue,amber,rose,purple,teal,cyan,indigo',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('why-choose-us', 'public');
            $validated['icon'] = $path;
        }

        // Default to active if not present (though validation handles boolean)
        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        try {
            WhyChooseUs::create($validated);

            CacheService::clear('why_choose_us_items');

            return redirect()->route('admin.why-choose-us.index')
                ->with('success', 'Item berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.why-choose-us.index')
                ->with('error', 'Gagal menambahkan item: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WhyChooseUs $whyChooseUs)
    {
        $this->authorizeEdit('why_choose_us.manage');
        return view('admin.why-choose-us.edit', compact('whyChooseUs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WhyChooseUs $whyChooseUs)
    {
        $this->authorizeEdit('why_choose_us.manage');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,svg,webp',
            'color_theme' => 'required|string|in:primary,emerald,blue,amber,rose,purple,teal,cyan,indigo',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('icon')) {
            if ($whyChooseUs->icon) {
                Storage::disk('public')->delete($whyChooseUs->icon);
            }
            $path = $request->file('icon')->store('why-choose-us', 'public');
            $validated['icon'] = $path;
        }

        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        try {
            $whyChooseUs->update($validated);

            CacheService::clear('why_choose_us_items');

            return redirect()->route('admin.why-choose-us.index')
                ->with('success', 'Item berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.why-choose-us.index')
                ->with('error', 'Gagal memperbarui item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhyChooseUs $whyChooseUs)
    {
        $this->authorizeDelete('why_choose_us.manage');
        if ($whyChooseUs->icon) {
            Storage::disk('public')->delete($whyChooseUs->icon);
        }

        try {
            $whyChooseUs->delete();

            CacheService::clear('why_choose_us_items');

            return redirect()->route('admin.why-choose-us.index')
                ->with('success', 'Item berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.why-choose-us.index')
                ->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    /**
     * Show the settings form.
     */
    public function editSettings()
    {
        $this->authorizeAny(['why_choose_us.settings']);
        $settings = WhyChooseUsSetting::getSettings();
        return view('admin.why-choose-us.settings', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function updateSettings(Request $request)
    {
        $this->authorizeAny(['why_choose_us.settings']);
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'section_subtitle' => 'required|string',
            'section_image' => 'nullable|image|max:4096|mimes:png,jpg,jpeg,webp',
            'badge_text' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $settings = WhyChooseUsSetting::getSettings();

        if ($request->hasFile('section_image')) {
            if ($settings->section_image) {
                Storage::disk('public')->delete($settings->section_image);
            }
            $path = $request->file('section_image')->store('why-choose-us', 'public');
            $validated['section_image'] = $path;
        }

        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        try {
            $settings->update($validated);

            CacheService::clear('why_choose_us_settings');

            return redirect()->route('admin.why-choose-us.settings')
                ->with('success', 'Pengaturan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.why-choose-us.settings')
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }
}
