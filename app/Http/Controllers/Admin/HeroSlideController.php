<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\SiteSetting;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeView('settings.hero');

        $slides = HeroSlide::orderBy('order_position')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function settings()
    {
        $this->authorizeView('settings.hero');
        $settings = SiteSetting::getSettings();
        return view('admin.hero-slides.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $this->authorizeUpdate('settings.hero');

        $validated = $request->validate([
            'hero_slider_delay' => 'required|integer|min:1000|max:20000',
        ]);

        $settings = SiteSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            SiteSetting::create($validated);
        }

        // Clear cache
        SiteSetting::clearCache();

        return redirect()->route('admin.hero-slides.index')->with('success', 'Pengaturan slider berhasil diperbarui.');
    }

    public function create()
    {
        $this->authorizeCreate('settings.hero');

        $transitionTypes = HeroSlide::getTransitionTypes();
        return view('admin.hero-slides.form', compact('transitionTypes'));
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('settings.hero');
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
            'transition_type' => 'nullable|string|max:50',
            'transition_duration' => 'nullable|integer|min:100|max:10000',
            'show_title' => 'boolean',
            'show_subtitle' => 'boolean',
            'show_button' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_title'] = $request->boolean('show_title');
        $validated['show_subtitle'] = $request->boolean('show_subtitle');
        $validated['show_button'] = $request->boolean('show_button');

        try {
            $validated['image'] = $this->handleImageUpload($request, 'image', 'hero-slides');

            if (!$validated['image']) {
                return back()->withInput()->with('error', 'Gambar slide wajib diupload.');
            }

            HeroSlide::create($validated);

            return redirect()->route('admin.hero-slides.index')->with('success', 'Slide berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating hero slide: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan slide. Silakan coba lagi.');
        }
    }

    public function edit(HeroSlide $heroSlide)
    {
        $this->authorizeEdit('settings.hero');

        $transitionTypes = HeroSlide::getTransitionTypes();
        return view('admin.hero-slides.form', compact('heroSlide', 'transitionTypes'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $this->authorizeEdit('settings.hero');
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
            'transition_type' => 'nullable|string|max:50',
            'transition_duration' => 'nullable|integer|min:100|max:10000',
            'show_title' => 'boolean',
            'show_subtitle' => 'boolean',
            'show_button' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_title'] = $request->boolean('show_title');
        $validated['show_subtitle'] = $request->boolean('show_subtitle');
        $validated['show_button'] = $request->boolean('show_button');

        try {
            $validated['image'] = $this->handleImageUpload($request, 'image', 'hero-slides', $heroSlide->image);

            $heroSlide->update($validated);

            return redirect()->route('admin.hero-slides.index')->with('success', 'Slide berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating hero slide: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui slide. Silakan coba lagi.');
        }
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $this->authorizeDelete('settings.hero');

        try {
            if ($heroSlide->image) {
                Storage::disk('public')->delete($heroSlide->image);
            }

            $heroSlide->delete();

            return redirect()->route('admin.hero-slides.index')->with('success', 'Slide berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting hero slide: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus slide. Silakan coba lagi.');
        }
    }

    public function reorder(Request $request)
    {
        $this->authorizeEdit('settings.hero');

        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $index => $id) {
            HeroSlide::where('id', $id)->update(['order_position' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
