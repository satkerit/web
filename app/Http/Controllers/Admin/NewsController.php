<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('news.view');
        $query = News::query()->with('user');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'published') {
                $query->published();
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $news = $query->latest()->paginate(10)->withQueryString();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $this->authorizeCreate('news.create');
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('news.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'category' => 'required|string',
            'featured_image' => 'required|image|max:2048',
            'slide_images.*' => 'image|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        // Increase execution time for multiple image processing
        set_time_limit(600); // 10 minutes

        DB::beginTransaction();
        try {
            $data = $validated;

            // Handle Slug
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Handle Checkbox
            $data['is_published'] = $request->boolean('is_published');

            // Handle Featured Image
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $this->storeOptimizedImage($request->file('featured_image'), 'news');
            }

            // Set Author
            $data['author'] = auth()->user()->name;
            $data['author_id'] = auth()->id();

            $news = News::create($data);

            // Handle Gallery Images
            if ($request->hasFile('slide_images')) {
                $slideImages = $request->file('slide_images');
                $maxImages = 7;

                if (count($slideImages) > $maxImages) {
                    throw new \Exception("Maksimal {$maxImages} gambar galeri yang diizinkan");
                }

                foreach ($slideImages as $image) {
                    $path = $this->storeOptimizedImage($image, 'news/gallery');
                    $news->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News creation failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menambahkan berita: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(News $news)
    {
        $this->authorizeEdit('news.edit');
        $news->load('images');
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeEdit('news.edit');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'category' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'slide_images.*' => 'image|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        // Increase execution time for multiple image processing
        set_time_limit(600); // 10 minutes

        DB::beginTransaction();
        try {
            $data = $validated;

            // Handle Checkbox
            $data['is_published'] = $request->boolean('is_published');

            // Handle Featured Image
            if ($request->hasFile('featured_image')) {
                // Delete old
                if ($news->featured_image) {
                    Storage::disk('public')->delete($news->featured_image);
                }
                $data['featured_image'] = $this->storeOptimizedImage($request->file('featured_image'), 'news');
            }

            $news->update($data);

            // Handle Gallery Images (Add New)
            if ($request->hasFile('slide_images')) {
                $slideImages = $request->file('slide_images');
                $currentImagesCount = $news->images()->count();
                $maxImages = 7; // Updated to match test expectation

                if (($currentImagesCount + count($slideImages)) > $maxImages) {
                    throw new \Exception("Maksimal total {$maxImages} gambar galeri yang diizinkan");
                }

                foreach ($slideImages as $index => $image) {
                    try {
                        $path = $this->storeOptimizedImage($image, 'news/gallery');
                        $news->images()->create(['image_path' => $path]);
                    } catch (\Exception $e) {
                        Log::error("Failed to process gallery image", ['error' => $e->getMessage()]);
                    }
                }
            }

            // Handle Gallery Images (Delete Selected)
            if ($request->has('delete_images')) {
                $imagesToDelete = NewsImage::whereIn('id', $request->delete_images)->where('news_id', $news->id)->get();
                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(News $news)
    {
        $this->authorizeDelete('news.delete');
        try {
            DB::beginTransaction();

            // Delete images from storage
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            foreach ($news->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $news->delete();

            DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('News deletion failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }

    public function deleteImage(NewsImage $newsImage)
    {
        $this->authorizeEdit('news.edit');
        try {
            Storage::disk('public')->delete($newsImage->image_path);
            $newsImage->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Foto slide berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Gagal menghapus gambar: ' . $e->getMessage());
        }
    }
}
