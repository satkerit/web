<?php

namespace App\Livewire\Admin\WhyChooseUs;

use App\Models\WhyChooseUs;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, Title};
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
#[Title('Kelola Why Choose Us')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTheme = '';
    public $filterStatus = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterTheme' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTheme()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function getThemesProperty()
    {
        return WhyChooseUs::getThemes();
    }

    public function toggleStatus($id)
    {
        $item = WhyChooseUs::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => 'Status berhasil diperbarui.'
        ]);
    }

    public function deleteItem($id)
    {
        $item = WhyChooseUs::findOrFail($id);
        
        // Delete icon if exists
        if ($item->icon) {
            Storage::disk('public')->delete($item->icon);
        }
        
        $item->delete();

        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => 'Item berhasil dihapus.'
        ]);
    }

    public function reorderItems($items)
    {
        foreach ($items as $index => $item) {
            WhyChooseUs::where('id', $item['id'])->update(['sort_order' => $index]);
        }

        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => 'Urutan berhasil diperbarui.'
        ]);
    }

    public function render()
    {
        $query = WhyChooseUs::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by theme
        if ($this->filterTheme) {
            $query->where('color_theme', $this->filterTheme);
        }

        // Filter by status
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        $items = $query->orderBy('sort_order')->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.why-choose-us.index', [
            'items' => $items,
            'themes' => $this->themes,
        ]);
    }
}
