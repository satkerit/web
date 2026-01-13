<?php

namespace App\Livewire\Frontend\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Cache;

#[Title('Berita & Artikel')]
#[Layout('components.frontend-layout')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function news()
    {
        return News::query()
            ->select(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'category'])
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->when($this->search, fn($q) => $q->where(
                fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('excerpt', 'like', "%{$this->search}%")
            ))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->orderBy('published_at', 'desc')
            ->paginate(12);
    }

    #[Computed]
    public function categories()
    {
        return Cache::remember(
            'news_categories',
            3600,
            fn() =>
            News::where('is_published', true)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
        );
    }

    public function render()
    {
        return view('livewire.frontend.news.index', [
            'news' => $this->news,
            'categories' => $this->categories,
        ]);
    }
}
