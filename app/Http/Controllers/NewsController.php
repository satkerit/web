<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query()
            ->select(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'category'])
            ->where('is_published', true)
            ->where('published_at', '<=', now());

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(12)->withQueryString();

        // Get categories for filter
        $categories = Cache::remember(
            'news_categories',
            3600,
            fn() => News::where('is_published', true)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
        );

        return view('frontend.pages.news.index', compact('news', 'categories'));
    }

    public function show(string $slug)
    {
        $news = News::with('images')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.pages.news.show', compact('news'));
    }
}
