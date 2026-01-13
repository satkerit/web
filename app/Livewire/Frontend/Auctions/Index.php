<?php

namespace App\Livewire\Frontend\Auctions;

use App\Models\Auction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

#[Title('Lelang Agunan')]
#[Layout('components.frontend-layout')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    #[Url(as: 'tipe')]
    public string $assetType = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingAssetType(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function auctions()
    {
        return Auction::query()
            ->select(['id', 'title', 'slug', 'location', 'starting_price', 'auction_date', 'status', 'asset_type', 'images'])
            ->when($this->search, fn($q) => $q->where(
                fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('location', 'like', "%{$this->search}%")
            ))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->assetType, fn($q) => $q->where('asset_type', $this->assetType))
            ->orderBy('auction_date', 'desc')
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.frontend.auctions.index', [
            'auctions' => $this->auctions,
        ]);
    }
}
