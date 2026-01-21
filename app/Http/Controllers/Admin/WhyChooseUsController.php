<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WhyChooseUsController extends Controller
{
    use HandlesImageUpload;
    // use AuthorizesAdminActions; // Temporarily commented if permission seed not ready

    public function index()
    {
        $items = WhyChooseUs::orderBy('sort_order')->get();
        return view('admin.why-choose-us.index', compact('items'));
    }

    public function create()
    {
        $themes = WhyChooseUs::getThemes();
        // Pass empty model with defaults to prevent undefined variable error in view
        $item = new WhyChooseUs();
        $item->is_active = true;

        return view('admin.why-choose-us.form', compact('themes', 'item'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'color_theme' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        try {
            if ($request->hasFile('icon')) {
                $validated['icon'] = $this->storeOptimizedImage($request->file('icon'), 'why-choose-us');
            }

            WhyChooseUs::create($validated);

            return redirect()->route('admin.why-choose-us.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating Why Choose Us item: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan data. Silakan coba lagi.');
        }
    }

    public function edit(WhyChooseUs $whyChooseUs)
    {
        $themes = WhyChooseUs::getThemes();
        $item = $whyChooseUs;
        
        return view('admin.why-choose-us.form', compact('item', 'themes', 'whyChooseUs'));
    }

    public function update(Request $request, WhyChooseUs $whyChooseUs)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'color_theme' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        try {
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($whyChooseUs->icon) {
                    Storage::disk('public')->delete($whyChooseUs->icon);
                }
                
                $validated['icon'] = $this->storeOptimizedImage($request->file('icon'), 'why-choose-us');
            }

            $whyChooseUs->update($validated);

            return redirect()->route('admin.why-choose-us.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating Why Choose Us item: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }
    }

    public function destroy(WhyChooseUs $whyChooseUs)
    {
        try {
            if ($whyChooseUs->icon) {
                Storage::disk('public')->delete($whyChooseUs->icon);
            }

            $whyChooseUs->delete();

            return redirect()->route('admin.why-choose-us.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting Why Choose Us item: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }
}
