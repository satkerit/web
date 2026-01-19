<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('news.view');

        $query = News::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $news = $query->paginate(15)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $this->authorizeCreate('news.create');

        return view('admin.news.form');
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('news.create');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|string|max:100',
            'is_published' => 'nullable',
            'published_at' => 'nullable|date',
            'slide_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slide_images' => 'nullable|array|max:3',
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'slug.unique' => 'Slug URL sudah digunakan, silakan gunakan yang lain.',
            'content.required' => 'Konten berita wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
            'featured_image.image' => 'File harus berupa gambar.',
            'featured_image.max' => 'Ukuran gambar maksimal 2MB.',
            'slide_images.max' => 'Maksimal 3 foto slide diperbolehkan.',
        ]);

        try {
            $validated['author_id'] = auth()->id();
            $validated['author'] = $validated['author'] ?: auth()->user()->name;
            $validated['is_published'] = $request->boolean('is_published');

            $validated['featured_image'] = $this->handleImageUpload($request, 'featured_image', 'news');

            $news = News::create($validated);

            if ($request->hasFile('slide_images')) {
                foreach ($request->file('slide_images') as $index => $image) {
                    $path = $image->store('news/slides', 'public');
                    $news->images()->create([
                        'image_path' => $path,
                        'order' => $index
                    ]);
                }
            }

            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan berita: ' . $e->getMessage());
        }
    }

    public function show(News $news)
    {
        $this->authorizeView('news.view');

        return redirect()->route('admin.news.edit', $news);
    }

    public function edit(News $news)
    {
        $this->authorizeEdit('news.edit');

        $news->load('images');
        return view('admin.news.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeEdit('news.edit');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|string|max:100',
            'is_published' => 'nullable',
            'published_at' => 'nullable|date',
            'slide_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slide_images' => 'nullable|array|max:3',
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'slug.unique' => 'Slug URL sudah digunakan, silakan gunakan yang lain.',
            'content.required' => 'Konten berita wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
            'featured_image.image' => 'File harus berupa gambar.',
            'featured_image.max' => 'Ukuran gambar maksimal 2MB.',
            'slide_images.max' => 'Maksimal 3 foto slide diperbolehkan.',
        ]);

        try {
            $validated['author'] = $validated['author'] ?: $news->author;
            $validated['is_published'] = $request->boolean('is_published');

            $validated['featured_image'] = $this->handleImageUpload($request, 'featured_image', 'news', $news->featured_image);

            $news->update($validated);

            if ($request->hasFile('slide_images')) {
                $currentCount = $news->images()->count();
                $newCount = count($request->file('slide_images'));

                if ($currentCount + $newCount > 3) {
                    return back()->withInput()->with('error', 'Total foto slide tidak boleh lebih dari 3. Silakan hapus foto lama terlebih dahulu.');
                }

                foreach ($request->file('slide_images') as $image) {
                    $path = $image->store('news/slides', 'public');
                    $news->images()->create([
                        'image_path' => $path,
                        'order' => $news->images()->max('order') + 1
                    ]);
                }
            }

            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage());
        }
    }

    public function destroy(News $news)
    {
        $this->authorizeDelete('news.delete');

        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        foreach ($news->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function destroyImage(NewsImage $image)
    {
        $this->authorizeEdit('news.edit');

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Foto slide berhasil dihapus.');
    }
}
