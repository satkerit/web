<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Helpers\HtmlSanitizer;
use Illuminate\Support\Str;

class NewsControllerSecure extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $this->authorizeView('news.view');

        $query = News::with(['images', 'user'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $news = $query->paginate(15)->appends($request->query());

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news.
     */
    public function create()
    {
        $this->authorizeCreate('news.create');
        
        return view('admin.news.form-redesign');
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeCreate('news.create');

        $validator = $this->validateNewsData($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $this->prepareNewsData($request);
            $news = News::create($data);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $imagePath = $this->handleImageUpload(
                    $request->file('featured_image'),
                    'news',
                    'featured_image'
                );
                $news->update(['featured_image' => $imagePath]);
            }

            // Handle gallery images
            if ($request->hasFile('slide_images')) {
                $this->handleGalleryImages($news, $request->file('slide_images'));
            }

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil dibuat dan dipublikasikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating news: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan berita. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Display the specified news.
     */
    public function show(News $news)
    {
        $this->authorizeView('news.view');
        
        $news->load(['images', 'user']);
        
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        $this->authorizeEdit('news.edit');
        
        $news->load('images');
        
        return view('admin.news.form-redesign', compact('news'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {
        $this->authorizeEdit('news.edit');

        $validator = $this->validateNewsData($request, $news->id);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $this->prepareNewsData($request);
            $news->update($data);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($news->featured_image) {
                    Storage::disk('public')->delete($news->featured_image);
                }
                
                $imagePath = $this->handleImageUpload(
                    $request->file('featured_image'),
                    'news',
                    'featured_image'
                );
                $news->update(['featured_image' => $imagePath]);
            }

            // Handle gallery images
            if ($request->hasFile('slide_images')) {
                $this->handleGalleryImages($news, $request->file('slide_images'));
            }

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating news: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui berita. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {
        $this->authorizeDelete('news.delete');

        try {
            DB::beginTransaction();

            // Delete featured image
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            // Delete gallery images
            foreach ($news->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $news->delete();

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting news: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus berita. Silakan coba lagi.');
        }
    }

    /**
     * Delete a specific image from news gallery.
     */
    public function destroyImage(NewsImage $image)
    {
        $this->authorizeEdit('news.edit');

        try {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            return redirect()->back()
                ->with('success', 'Gambar berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting news image: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus gambar.');
        }
    }

    /**
     * Validate news data.
     */
    private function validateNewsData(Request $request, $newsId = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('news')->ignore($newsId)
            ],
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|in:Berita,Artikel,Pengumuman,Promo,Event',
            'author' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slide_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];

        $messages = [
            'title.required' => 'Judul berita wajib diisi.',
            'title.max' => 'Judul berita maksimal 255 karakter.',
            'slug.required' => 'Slug URL wajib diisi.',
            'slug.unique' => 'Slug URL sudah digunakan.',
            'slug.regex' => 'Slug URL hanya boleh mengandung huruf kecil, angka, dan tanda hubung.',
            'content.required' => 'Konten berita wajib diisi.',
            'category.required' => 'Kategori berita wajib dipilih.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'excerpt.max' => 'Ringkasan maksimal 500 karakter.',
            'author.max' => 'Nama penulis maksimal 100 karakter.',
            'meta_description.max' => 'Meta description maksimal 160 karakter.',
            'tags.max' => 'Tags maksimal 255 karakter.',
            'featured_image.image' => 'File gambar utama harus berupa gambar.',
            'featured_image.mimes' => 'Gambar utama harus berformat JPEG, PNG, JPG, atau WebP.',
            'featured_image.max' => 'Ukuran gambar utama maksimal 2MB.',
            'slide_images.*.image' => 'File galeri harus berupa gambar.',
            'slide_images.*.mimes' => 'Gambar galeri harus berformat JPEG, PNG, JPG, atau WebP.',
            'slide_images.*.max' => 'Ukuran gambar galeri maksimal 2MB.'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Prepare news data for storage.
     */
    private function prepareNewsData(Request $request)
    {
        $data = $request->only([
            'title', 'slug', 'content', 'excerpt', 'category', 
            'author', 'meta_description', 'tags', 'published_at', 'is_published'
        ]);

        // Sanitize HTML content
        $data['content'] = HtmlSanitizer::sanitize($data['content']);

        // Generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Set default author
        if (empty($data['author'])) {
            $data['author'] = auth()->user()->name;
        }

        // Set author_id (not user_id)
        $data['author_id'] = auth()->id();

        // Handle published_at
        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Convert is_published to boolean
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    /**
     * Handle gallery images upload.
     */
    private function handleGalleryImages(News $news, array $images)
    {
        $currentImagesCount = $news->images()->count();
        $maxImages = 3;
        $remainingSlots = $maxImages - $currentImagesCount;

        if ($remainingSlots <= 0) {
            return;
        }

        $imagesToProcess = array_slice($images, 0, $remainingSlots);
        $currentOrder = $news->images()->max('order') ?? -1;

        foreach ($imagesToProcess as $image) {
            $imagePath = $this->handleImageUpload($image, 'news/slides', 'gallery');
            
            NewsImage::create([
                'news_id' => $news->id,
                'image_path' => $imagePath,
                'order' => ++$currentOrder
            ]);
        }
    }
}