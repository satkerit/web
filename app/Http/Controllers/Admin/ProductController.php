<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('products.view');

        $query = Product::orderBy('order_position')->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorizeCreate('products.create');

        return view('admin.products.create');
    }

    public function show(Product $product)
    {
        $this->authorizeView('products.view');

        return view('admin.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('products.create');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:simpanan_syariah,pembiayaan_syariah,deposito_syariah',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'interest_rate' => 'nullable|string|max:100',
            'features' => 'nullable|array',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Set default description if empty
        if (empty($validated['description'])) {
            $validated['description'] = $validated['short_description'] ?? 'Deskripsi produk';
        }

        // Filter empty values from arrays
        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter($validated['requirements'], fn($v) => !empty(trim($v))));
        }

        try {
            $validated['image'] = $this->handleImageUpload($request, 'image', 'products');

            Product::create($validated);

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan produk. Silakan coba lagi.');
        }
    }

    public function edit(Product $product)
    {
        $this->authorizeEdit('products.edit');

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeEdit('products.edit');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:simpanan_syariah,pembiayaan_syariah,deposito_syariah',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'interest_rate' => 'nullable|string|max:100',
            'features' => 'nullable|array',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Set default description if empty
        if (empty($validated['description'])) {
            $validated['description'] = $validated['short_description'] ?? 'Deskripsi produk';
        }

        // Filter empty values from arrays
        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_values(array_filter($validated['requirements'], fn($v) => !empty(trim($v))));
        }

        try {
            $validated['image'] = $this->handleImageUpload($request, 'image', 'products', $product->image);

            $product->update($validated);

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.');
        }
    }

    public function destroy(Product $product)
    {
        $this->authorizeDelete('products.delete');

        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus produk. Silakan coba lagi.');
        }
    }
}
