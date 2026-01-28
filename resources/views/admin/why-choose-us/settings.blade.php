@extends('layouts.admin')

@section('title', 'Pengaturan Section Why Choose Us')

@section('content')
<x-admin.page-header 
    title="Pengaturan Section" 
    subtitle="Kelola gambar dan teks untuk section Why Choose Us di halaman utama">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.why-choose-us.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kelola Items
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ route('admin.why-choose-us-settings.update') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      id="settingsForm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Text Content --}}
            <x-admin.card title="Konten Teks" subtitle="Judul dan subtitle section">
                <div class="space-y-5">
                    <!-- Section Title -->
                    <div>
                        <label for="section_title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Section <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="section_title"
                               id="section_title"
                               value="{{ old('section_title', $setting->section_title) }}"
                               required
                               placeholder="Contoh: Mengapa Memilih Kami"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('section_title') border-red-500 @enderror">
                        @error('section_title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Subtitle -->
                    <div>
                        <label for="section_subtitle" class="block text-sm font-semibold text-gray-700 mb-2">
                            Subtitle Section
                        </label>
                        <textarea name="section_subtitle"
                                  id="section_subtitle"
                                  rows="3"
                                  placeholder="Deskripsi singkat tentang keunggulan Anda..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('section_subtitle') border-red-500 @enderror">{{ old('section_subtitle', $setting->section_subtitle) }}</textarea>
                        @error('section_subtitle')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Badge Text -->
                    <div>
                        <label for="badge_text" class="block text-sm font-semibold text-gray-700 mb-2">
                            Teks Badge
                        </label>
                        <input type="text"
                               name="badge_text"
                               id="badge_text"
                               value="{{ old('badge_text', $setting->badge_text) }}"
                               placeholder="Contoh: 100% Syariah Compliant"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('badge_text') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1.5">Badge yang muncul di pojok gambar section</p>
                        @error('badge_text')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>

            {{-- Images --}}
            <x-admin.card title="Gambar Section" subtitle="Upload gambar utama dan icon badge">
                <div class="space-y-6">
                    <!-- Section Image -->
                    <div>
                        <label for="section_image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Gambar Section Utama
                        </label>
                        
                        @if($setting->section_image)
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <img src="{{ \App\Helpers\StorageHelper::url($setting->section_image) }}" 
                                     alt="Current section image" 
                                     class="w-full h-48 object-cover rounded mx-auto"
                                     onerror="this.parentElement.style.display='none';">
                                <p class="text-xs text-gray-500 text-center mt-2">Gambar section saat ini</p>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file"
                                   name="section_image"
                                   id="section_image"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="hidden">
                            <label for="section_image" 
                                   class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 font-medium mb-1">Upload Gambar Section</p>
                                    <p class="text-xs text-gray-400">PNG, JPG, WEBP (Max 5MB)</p>
                                </div>
                            </label>
                        </div>

                        <!-- Section Image Preview -->
                        <div id="sectionImagePreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="" alt="Section preview" class="w-full h-48 object-cover rounded mx-auto" id="sectionImagePreviewImg">
                            <p class="text-xs text-gray-500 text-center mt-2">Preview Gambar</p>
                            <button type="button" 
                                    onclick="clearSectionImagePreview()"
                                    class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                                Batal
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            <strong>Rekomendasi:</strong> Gambar landscape (1200x800px) untuk tampilan optimal
                        </p>
                        @error('section_image')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-6"></div>

                    <!-- Badge Icon -->
                    <div>
                        <label for="badge_icon" class="block text-sm font-semibold text-gray-700 mb-2">
                            Icon Badge
                        </label>
                        
                        @if($setting->badge_icon)
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <img src="{{ \App\Helpers\StorageHelper::url($setting->badge_icon) }}" 
                                     alt="Current badge icon" 
                                     class="w-16 h-16 object-contain mx-auto"
                                     onerror="this.parentElement.style.display='none';">
                                <p class="text-xs text-gray-500 text-center mt-2">Icon badge saat ini</p>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file"
                                   name="badge_icon"
                                   id="badge_icon"
                                   accept="image/png,image/svg+xml,image/jpeg,image/webp"
                                   class="hidden">
                            <label for="badge_icon" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-xs text-gray-500 font-medium">Upload Icon Badge</p>
                                    <p class="text-xs text-gray-400">PNG, SVG (Max 2MB)</p>
                                </div>
                            </label>
                        </div>

                        <!-- Badge Icon Preview -->
                        <div id="badgeIconPreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="" alt="Badge icon preview" class="w-16 h-16 object-contain mx-auto" id="badgeIconPreviewImg">
                            <p class="text-xs text-gray-500 text-center mt-2">Preview Icon</p>
                            <button type="button" 
                                    onclick="clearBadgeIconPreview()"
                                    class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                                Batal
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            <strong>Rekomendasi:</strong> Icon SVG atau PNG transparan (48x48px)
                        </p>
                        @error('badge_icon')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Preview --}}
            <x-admin.card title="Preview" subtitle="Tampilan section">
                <div class="space-y-4">
                    <div class="relative rounded-lg overflow-hidden border border-gray-200">
                        @if($setting->section_image)
                            <img src="{{ \App\Helpers\StorageHelper::url($setting->section_image) }}" 
                                 alt="Preview" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-48 bg-gray-100 items-center justify-center" style="display: none;">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        @if($setting->badge_text)
                            <div class="absolute bottom-4 right-4 bg-white rounded-lg shadow-lg p-3 flex items-center gap-2">
                                @if($setting->badge_icon)
                                    <img src="{{ \App\Helpers\StorageHelper::url($setting->badge_icon) }}" 
                                         alt="Badge" 
                                         class="w-6 h-6"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm font-semibold text-gray-900">{{ $setting->badge_text }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-gray-900">{{ $setting->section_title }}</h3>
                        @if($setting->section_subtitle)
                            <p class="text-sm text-gray-600 mt-1">{{ $setting->section_subtitle }}</p>
                        @endif
                    </div>
                </div>
            </x-admin.card>

            {{-- Settings --}}
            <x-admin.card title="Status" subtitle="Aktifkan/nonaktifkan section">
                <div class="pt-2">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span class="text-sm font-semibold text-gray-700">Tampilkan Section</span>
                            <p class="text-xs text-gray-500 mt-0.5">Aktifkan untuk menampilkan di frontend</p>
                        </div>
                        <div class="relative">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="sr-only peer"
                                   {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </div>
                    </label>
                </div>
            </x-admin.card>

            {{-- Action Buttons --}}
            <x-admin.card :noPadding="true">
                <div class="p-5 space-y-3">
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengaturan
                    </button>
                    
                    <a href="{{ route('admin.why-choose-us.index') }}" 
                       class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </x-admin.card>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Why Choose Us Settings Script Loaded');
    
    // Section Image Preview
    const sectionImageInput = document.getElementById('section_image');
    const sectionImagePreview = document.getElementById('sectionImagePreview');
    const sectionImagePreviewImg = document.getElementById('sectionImagePreviewImg');
    
    if (sectionImageInput) {
        console.log('Section image input found');
        sectionImageInput.addEventListener('change', function(event) {
            console.log('Section image changed');
            handleImagePreview(event, 'section');
        });
    } else {
        console.error('Section image input not found');
    }
    
    // Badge Icon Preview
    const badgeIconInput = document.getElementById('badge_icon');
    const badgeIconPreview = document.getElementById('badgeIconPreview');
    const badgeIconPreviewImg = document.getElementById('badgeIconPreviewImg');
    
    if (badgeIconInput) {
        console.log('Badge icon input found');
        badgeIconInput.addEventListener('change', function(event) {
            console.log('Badge icon changed');
            handleImagePreview(event, 'badge');
        });
    } else {
        console.error('Badge icon input not found');
    }
    
    // Generic image preview handler
    function handleImagePreview(event, type) {
        console.log('Handling image preview for type:', type);
        const file = event.target.files[0];
        
        if (!file) {
            console.log('No file selected');
            return;
        }

        console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

        // Define validation rules based on type
        let allowedTypes, maxSize, previewElement, previewImg;
        
        if (type === 'section') {
            allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
            maxSize = 5 * 1024 * 1024; // 5MB
            previewElement = sectionImagePreview;
            previewImg = sectionImagePreviewImg;
        } else if (type === 'badge') {
            allowedTypes = ['image/png', 'image/svg+xml', 'image/jpeg', 'image/webp'];
            maxSize = 2 * 1024 * 1024; // 2MB
            previewElement = badgeIconPreview;
            previewImg = badgeIconPreviewImg;
        }

        // Validate file type
        if (!allowedTypes.includes(file.type)) {
            const typeText = type === 'section' ? 'PNG, JPG, JPEG, dan WEBP' : 'PNG, SVG, JPEG, dan WEBP';
            alert(`Hanya file ${typeText} yang diperbolehkan.`);
            event.target.value = '';
            return;
        }

        // Validate file size
        if (file.size > maxSize) {
            const sizeText = type === 'section' ? '5MB' : '2MB';
            alert(`Ukuran file maksimal adalah ${sizeText}.`);
            event.target.value = '';
            return;
        }

        console.log('File validation passed, creating preview...');

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                console.log('FileReader loaded successfully');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    console.log('Preview image src set');
                }
                if (previewElement) {
                    previewElement.classList.remove('hidden');
                    console.log('Preview element shown');
                }
            } catch (error) {
                console.error('Error showing preview:', error);
                alert('Terjadi kesalahan saat menampilkan preview gambar.');
            }
        };
        
        reader.onerror = function() {
            console.error('FileReader error');
            alert('Terjadi kesalahan saat membaca file.');
            event.target.value = '';
        };
        
        reader.readAsDataURL(file);
    }
    
    // Clear preview functions
    window.clearSectionImagePreview = function() {
        console.log('Clearing section image preview');
        try {
            if (sectionImageInput) sectionImageInput.value = '';
            if (sectionImagePreviewImg) sectionImagePreviewImg.src = '';
            if (sectionImagePreview) sectionImagePreview.classList.add('hidden');
        } catch (error) {
            console.error('Error clearing section image preview:', error);
        }
    };

    window.clearBadgeIconPreview = function() {
        console.log('Clearing badge icon preview');
        try {
            if (badgeIconInput) badgeIconInput.value = '';
            if (badgeIconPreviewImg) badgeIconPreviewImg.src = '';
            if (badgeIconPreview) badgeIconPreview.classList.add('hidden');
        } catch (error) {
            console.error('Error clearing badge icon preview:', error);
        }
    };
    
    // Form validation before submit
    const form = document.getElementById('settingsForm');
    if (form) {
        console.log('Form found, adding submit validation');
        form.addEventListener('submit', function(event) {
            console.log('Form submit triggered');
            const sectionTitle = document.getElementById('section_title');
            
            if (!sectionTitle || !sectionTitle.value.trim()) {
                event.preventDefault();
                alert('Judul Section wajib diisi.');
                if (sectionTitle) sectionTitle.focus();
                return false;
            }
            
            console.log('Form validation passed');
            return true;
        });
    } else {
        console.error('Form not found');
    }
});
</script>
@endpush

@endsection
