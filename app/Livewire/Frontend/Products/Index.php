<?php

namespace App\Livewire\Frontend\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

#[Title('Produk & Layanan')]
#[Layout('frontend.layouts.app')]
class Index extends Component
{
    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'tipe')]
    public $type = '';

    public $selectedProduct = null;
    public $showModal = false;

    public function selectProduct($id)
    {
        $this->selectedProduct = Product::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->select(['id', 'name', 'slug', 'type', 'short_description', 'image', 'order_position'])
            ->where('is_active', true)
            ->when($this->search, fn($q) => $q->where(
                fn($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('short_description', 'like', "%{$this->search}%")
            ))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->orderBy('order_position')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.frontend.products.index', [
            'products' => $this->products,
        ]);
    }
}
