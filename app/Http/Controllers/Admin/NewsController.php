<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news',
            'content' => 'required',
            'category' => 'required|string',
            'featured_image' => 'required|image|max:2048',
            'slide_images.*' => 'image|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['featured_image', 'slide_images', '_token']);

            // Handle Slug
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Handle Checkbox
            $data['is_published'] = $request->has('is_published') ? 1 : 0;

            // Handle Featured Image
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $this->storeOptimizedImage($request->file('featured_image'), 'news');
            }

            // Set Author
            $data['author'] = auth()->user()->name;

            $news = News::create($data);

            // Handle Gallery Images
            if ($request->hasFile('slide_images')) {
                foreach ($request->file('slide_images') as $image) {
                    $path = $this->storeOptimizedImage($image, 'news/gallery');
                    $news->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan berita: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(News $news)
    {
        $news->load('images');
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'content' => 'required',
            'category' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'slide_images.*' => 'image|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['featured_image', 'slide_images', 'delete_images', '_token', '_method']);

            // Handle Checkbox
            $data['is_published'] = $request->has('is_published') ? 1 : 0;

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
                foreach ($request->file('slide_images') as $image) {
                    $path = $this->storeOptimizedImage($image, 'news/gallery');
                    $news->images()->create(['image_path' => $path]);
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
            return back()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(News $news)
    {
        DB::beginTransaction();
        try {
            // Delete Featured Image
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            // Delete Gallery Images
            foreach ($news->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $news->images()->delete(); // Bulk delete records

            $news->delete();

            DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }
}
