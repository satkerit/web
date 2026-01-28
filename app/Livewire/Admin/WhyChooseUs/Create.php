<?php

namespace App\Livewire\Admin\WhyChooseUs;

use App\Models\WhyChooseUs;
use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use App\Traits\HandlesImageUpload;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
#[Title('Tambah Why Choose Us')]
class Create extends Component
{
    use HandlesImageUpload;

    public $title = '';
    public $description = '';
    public $icon;
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

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'color_theme' => $this->color_theme,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            // Handle icon upload
            if ($this->icon) {
                $data['icon'] = $this->storeOptimizedImage(
                    $this->icon,
                    'why-choose-us/icons'
                );
            }

            WhyChooseUs::create($data);

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Why Choose Us berhasil ditambahkan.'
            ]);

            return $this->redirect(route('admin.why-choose-us.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal menambahkan item. Silakan coba lagi.'
            ]);
        }
    }

    public function getThemesProperty()
    {
        return WhyChooseUs::getThemes();
    }

    public function render()
    {
        return view('livewire.admin.why-choose-us.create', [
            'themes' => $this->themes,
        ]);
    }
}
