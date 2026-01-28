<?php

namespace App\Livewire\Admin\WhyChooseUs;

use App\Models\WhyChooseUs;
use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use App\Traits\HandlesImageUpload;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
#[Title('Edit Why Choose Us')]
class Edit extends Component
{
    use HandlesImageUpload;

    public $itemId;
    public $title = '';
    public $description = '';
    public $icon;
    public $current_icon;
    public $color_theme = 'primary';
    public $sort_order = 0;
    public $is_active = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'color_theme' => 'required|string|in:primary,emerald,blue,amber,rose,purple,teal,cyan,indigo',
        'sort_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'Judul wajib diisi.',
        'title.max' => 'Judul maksimal 255 karakter.',
        'description.required' => 'Deskripsi wajib diisi.',
        'icon.image' => 'File harus berupa gambar.',
        'icon.mimes' => 'Format icon yang diperbolehkan: JPEG, PNG, JPG, WEBP, SVG.',
        'icon.max' => 'Ukuran icon maksimal 2MB.',
        'color_theme.required' => 'Tema warna wajib dipilih.',
        'color_theme.in' => 'Tema warna tidak valid.',
        'sort_order.required' => 'Urutan wajib diisi.',
        'sort_order.min' => 'Urutan minimal 0.',
    ];

    public function mount($id)
    {
        $this->itemId = $id;
        $item = WhyChooseUs::findOrFail($id);

        $this->title = $item->title;
        $this->description = $item->description;
        $this->color_theme = $item->color_theme;
        $this->sort_order = $item->sort_order;
        $this->is_active = $item->is_active;
        $this->current_icon = $item->icon;
    }

    public function updatedIcon()
    {
        $this->validateOnly('icon');
    }

    public function save()
    {
        $this->validate();

        try {
            $item = WhyChooseUs::findOrFail($this->itemId);

            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'color_theme' => $this->color_theme,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            // Handle icon upload
            if ($this->icon) {
                // Delete old icon if exists
                if ($this->current_icon) {
                    Storage::disk('public')->delete($this->current_icon);
                }
                
                $data['icon'] = $this->storeOptimizedImage(
                    $this->icon,
                    'why-choose-us/icons'
                );
            }

            $item->update($data);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Why Choose Us berhasil diperbarui.'
            ]);

            return $this->redirect(route('admin.why-choose-us.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal memperbarui item. Silakan coba lagi.'
            ]);
        }
    }

    public function removeIcon()
    {
        try {
            if ($this->current_icon) {
                Storage::disk('public')->delete($this->current_icon);
                
                $item = WhyChooseUs::findOrFail($this->itemId);
                $item->update(['icon' => null]);
                
                $this->current_icon = null;
                
                $this->dispatch('showToast', [
                    'type' => 'success',
                    'message' => 'Icon berhasil dihapus.'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal menghapus icon. Silakan coba lagi.'
            ]);
        }
    }

    public function getThemesProperty()
    {
        return WhyChooseUs::getThemes();
    }

    public function getImageUrl($path)
    {
        return $path ? \App\Helpers\StorageHelper::url($path) : null;
    }

    public function render()
    {
        return view('livewire.admin.why-choose-us.edit', [
            'themes' => $this->themes,
        ]);
    }
}
