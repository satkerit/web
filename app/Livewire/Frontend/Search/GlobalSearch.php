<?php

namespace App\Livewire\Frontend\Search;

use App\Models\News;
use App\Models\Product;
use App\Models\Auction;
use Livewire\Component;
use Illuminate\Support\Str;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];
    public $showResults = false;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->showResults = false;
            return;
        }

        $this->search();
    }

    public function search()
    {
        $searchTerm = '%' . $this->query . '%';

        // Search News - only select needed columns
        $news = News::query()
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at'])
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where(fn($q) => $q->where('title', 'like', $searchTerm)->orWhere('excerpt', 'like', $searchTerm))
            ->limit(3)
            ->get()
            ->map(fn($item) => [
                'type' => 'Berita',
                'title' => $item->title,
                'excerpt' => $item->excerpt,
                'url' => route('news.show', $item->slug),
                'date' => $item->published_at->format('d M Y'),
            ]);

        // Search Products - only select needed columns
        $products = Product::query()
            ->select(['id', 'name', 'slug', 'short_description'])
            ->where('is_active', true)
            ->where(fn($q) => $q->where('name', 'like', $searchTerm)->orWhere('short_description', 'like', $searchTerm))
            ->limit(3)
            ->get()
            ->map(fn($item) => [
                'type' => 'Produk',
                'title' => $item->name,
                'excerpt' => $item->short_description,
                'url' => route('products.show', $item->slug),
                'date' => null,
            ]);

        // Search Auctions - only select needed columns
        $auctions = Auction::query()
            ->select(['id', 'title', 'slug', 'description', 'auction_date'])
            ->where(fn($q) => $q->where('title', 'like', $searchTerm)->orWhere('description', 'like', $searchTerm))
            ->limit(3)
            ->get()
            ->map(fn($item) => [
                'type' => 'Lelang',
                'title' => $item->title,
                'excerpt' => Str::limit($item->description, 100),
                'url' => route('auctions.show', $item->slug),
                'date' => $item->auction_date->format('d M Y'),
            ]);

        $this->results = $news->concat($products)->concat($auctions)->toArray();
        $this->showResults = true;
    }

    public function closeResults()
    {
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.frontend.search.global-search');
    }
}
