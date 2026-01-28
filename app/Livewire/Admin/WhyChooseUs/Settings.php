<?php

namespace App\Livewire\Admin\WhyChooseUs;

use App\Models\WhyChooseUsSetting;
use App\Traits\HandlesImageUpload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class Settings extends Component
{
    use WithFileUploads, HandlesImageUpload;

    public $section_title;
    public $section_subtitle;
    public $section_image;
    public $badge_text;
    public $badge_icon;
    public $is_active = true;
    
    public $current_section_image;
    public $current_badge_icon;
    
    protected $rules = [
        'section_title' => 'required|string|max:255',
        'section_subtitle' => 'nullable|string',
        'section_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'badge_text' => 'nullable|string|max:255',
        'badge_icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'section_title.required' => 'Judul section wajib diisi.',
        'section_title.max' => 'Judul section maksimal 255 karakter.',
        'section_subtitle.string' => 'Subtitle harus berupa teks.',
        'section_image.image' => 'File harus berupa gambar.',
        'section_image.mimes' => 'Format gambar yang diperbolehkan: JPEG, PNG, JPG, WEBP.',
        'section_image.max' => 'Ukuran gambar maksimal 5MB.',
        'badge_text.max' => 'Teks badge maksimal 255 karakter.',
        'badge_icon.image' => 'File harus berupa gambar.',
        'badge_icon.mimes' => 'Format icon yang diperbolehkan: JPEG, PNG, JPG, WEBP, SVG.',
        'badge_icon.max' => 'Ukuran icon maksimal 2MB.',
    ];

    public function mount()
    {
        $setting = WhyChooseUsSetting::getSettings();
        
        $this->section_title = $setting->section_title;
        $this->section_subtitle = $setting->section_subtitle;
        $this->badge_text = $setting->badge_text;
        $this->is_active = $setting->is_active;
        $this->current_section_image = $setting->section_image;
        $this->current_badge_icon = $setting->badge_icon;
    }

    public function updatedSectionImage()
    {
        $this->validateOnly('section_image');
    }

    public function updatedBadgeIcon()
    {
        $this->validateOnly('badge_icon');
    }

    public function save()
    {
        $this->validate();
        
        try {
            $setting = WhyChooseUsSetting::getSettings();
            
            $data = [
                'section_title' => $this->section_title,
                'section_subtitle' => $this->section_subtitle,
                'badge_text' => $this->badge_text,
                'is_active' => $this->is_active,
            ];

            // Handle section image upload
            if ($this->section_image) {
                // Delete old image if exists
                if ($this->current_section_image) {
                    Storage::disk('public')->delete($this->current_section_image);
                }
                
                $data['section_image'] = $this->storeOptimizedImage(
                    $this->section_image,
                    'why-choose-us/section'
                );
            }

            // Handle badge icon upload
            if ($this->badge_icon) {
                // Delete old icon if exists
                if ($this->current_badge_icon) {
                    Storage::disk('public')->delete($this->current_badge_icon);
                }
                
                $data['badge_icon'] = $this->storeOptimizedImage(
                    $this->badge_icon,
                    'why-choose-us/badges'
                );
            }

            $setting->update($data);
            
            // Update current images for display
            if (isset($data['section_image'])) {
                $this->current_section_image = $data['section_image'];
                $this->section_image = null;
            }
            
            if (isset($data['badge_icon'])) {
                $this->current_badge_icon = $data['badge_icon'];
                $this->badge_icon = null;
            }

            $this->dispatch('showToast', [
                'type' => 'success',
                'message' => 'Pengaturan section berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating Why Choose Us settings: ' . $e->getMessage());
            
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal memperbarui pengaturan. Silakan coba lagi.'
            ]);
        }
    }

    public function removeSectionImage()
    {
        try {
            if ($this->current_section_image) {
                Storage::disk('public')->delete($this->current_section_image);
                
                $setting = WhyChooseUsSetting::getSettings();
                $setting->update(['section_image' => null]);
                
                $this->current_section_image = null;
                
                $this->dispatch('showToast', [
                    'type' => 'success',
                    'message' => 'Gambar section berhasil dihapus.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error removing section image: ' . $e->getMessage());
            
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal menghapus gambar. Silakan coba lagi.'
            ]);
        }
    }

    public function removeBadgeIcon()
    {
        try {
            if ($this->current_badge_icon) {
                Storage::disk('public')->delete($this->current_badge_icon);
                
                $setting = WhyChooseUsSetting::getSettings();
                $setting->update(['badge_icon' => null]);
                
                $this->current_badge_icon = null;
                
                $this->dispatch('showToast', [
                    'type' => 'success',
                    'message' => 'Icon badge berhasil dihapus.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error removing badge icon: ' . $e->getMessage());
            
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Gagal menghapus icon. Silakan coba lagi.'
            ]);
        }
    }

    public function getImageUrl($path)
    {
        return $path ? \App\Helpers\StorageHelper::url($path) : null;
    }

    public function render()
    {
        return view('livewire.admin.why-choose-us.settings')
            ->layout('layouts.admin');
    }
}
