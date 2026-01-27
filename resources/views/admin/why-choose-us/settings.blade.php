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
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('section_title') border-red-500 @enderror">
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
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none @error('section_subtitle') border-red-500 @enderror">{{ old('section_subtitle', $setting->section_subtitle) }}</textarea>
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
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('badge_text') border-red-500 @enderror">
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
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200" id="currentSectionImageContainer">
                                <img src="{{ \App\Helpers\StorageHelper::url($setting->section_image) }}" 
                                     alt="Current section image" 
                                     class="w-full h-48 object-cover rounded mx-auto"
                                     id="currentSectionImage">
                                <p class="text-xs text-gray-500 text-center mt-2">Gambar section saat ini</p>
                                <button type="button" 
                                        onclick="removeCurrentSectionImage()"
                                        class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                                    Hapus Gambar
                                </button>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file"
                                   name="section_image"
                                   id="section_image"
                                   accept="image/png,image/jpeg,image/jpg,image/webp"
                                   class="hidden"
                                   onchange="previewSectionImage(event)"
                                   aria-describedby="section-image-help">
                            <label for="section_image" 
                                   class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 hover:bg-primary-50 transition-all"
                                   role="button"
                                   tabindex="0"
                                   onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();document.getElementById('section_image').click();}">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 font-medium mb-1">Upload Gambar Section</p>
                                    <p class="text-xs text-gray-400" id="section-image-help">PNG, JPG, WEBP (Max 5MB)</p>
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

                        <!-- ARIA Live Region for Screen Readers -->
                        <div id="sectionImageStatus" class="sr-only" aria-live="polite" aria-atomic="true"></div>

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
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200" id="currentBadgeIconContainer">
                                <img src="{{ \App\Helpers\StorageHelper::url($setting->badge_icon) }}" 
                                     alt="Current badge icon" 
                                     class="w-16 h-16 object-contain mx-auto"
                                     id="currentBadgeIcon">
                                <p class="text-xs text-gray-500 text-center mt-2">Icon badge saat ini</p>
                                <button type="button" 
                                        onclick="removeCurrentBadgeIcon()"
                                        class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                                    Hapus Icon
                                </button>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file"
                                   name="badge_icon"
                                   id="badge_icon"
                                   accept="image/png,image/svg+xml,image/jpeg,image/webp"
                                   class="hidden"
                                   onchange="previewBadgeIcon(event)"
                                   aria-describedby="badge-icon-help">
                            <label for="badge_icon" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 hover:bg-primary-50 transition-all"
                                   role="button"
                                   tabindex="0"
                                   onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();document.getElementById('badge_icon').click();}">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-xs text-gray-500 font-medium">Upload Icon Badge</p>
                                    <p class="text-xs text-gray-400" id="badge-icon-help">PNG, SVG (Max 2MB)</p>
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

                        <!-- ARIA Live Region for Screen Readers -->
                        <div id="badgeIconStatus" class="sr-only" aria-live="polite" aria-atomic="true"></div>

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
                                 class="w-full h-48 object-cover">
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
                                         class="w-6 h-6">
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
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </div>
                    </label>
                </div>
            </x-admin.card>

            {{-- Action Buttons --}}
            <x-admin.card :noPadding="true">
                <div class="p-5 space-y-3">
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    'use strict';
    
    console.log('Why Choose Us Settings Script Loaded');
    
    window.previewSectionImage = function(event) {
        console.log('previewSectionImage called');
        const file = event.target.files[0];
        
        if (!file) {
            console.log('No file selected');
            return;
        }

        // Validate file type
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: 'Hanya file PNG, JPG, JPEG, dan WEBP yang diperbolehkan.',
                confirmButtonColor: '#dc2626'
            });
            event.target.value = '';
            return;
        }

        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 5MB.',
                confirmButtonColor: '#dc2626'
            });
            event.target.value = '';
            return;
        }

        // Show loading state
        const previewContainer = document.getElementById('sectionImagePreview');
        const previewImg = document.getElementById('sectionImagePreviewImg');
        const statusElement = document.getElementById('sectionImageStatus');
        
        // Add loading indicator
        previewImg.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMTgiIHN0cm9rZT0iI2U1ZTdlYiIgc3Ryb2tlLXdpZHRoPSI0Ii8+CjxwYXRoIGQ9Im0zOCAyMGMwLTkuOTQxLTguMDU5LTE4LTE4LTE4IiBzdHJva2U9IiM2MzY2ZjEiIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIj4KPGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJyb3RhdGUiIGR1cj0iMXMiIHZhbHVlcz0iMCAyMCAyMDszNjAgMjAgMjAiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIi8+CjwvcGF0aD4KPC9zdmc+';
        previewContainer.classList.remove('hidden');
        if (statusElement) statusElement.textContent = 'Memuat preview gambar...';
        
        // Hide current image container
        const currentContainer = document.getElementById('currentSectionImageContainer');
        if (currentContainer) currentContainer.classList.add('hidden');

        try {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    previewImg.src = e.target.result;
                    if (statusElement) statusElement.textContent = 'Preview gambar berhasil dimuat';
                    console.log('Section image preview loaded successfully');
                } catch (error) {
                    console.error('Error setting preview image:', error);
                    handlePreviewError();
                }
            };
            
            reader.onerror = function(error) {
                console.error('FileReader error:', error);
                handlePreviewError();
            };
            
            reader.readAsDataURL(file);
            
        } catch (error) {
            console.error('Error reading file:', error);
            handlePreviewError();
        }

        function handlePreviewError() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Preview',
                text: 'Terjadi kesalahan saat memuat preview gambar. Silakan coba lagi.',
                confirmButtonColor: '#dc2626'
            });
            
            // Reset state
            event.target.value = '';
            previewContainer.classList.add('hidden');
            if (currentContainer) currentContainer.classList.remove('hidden');
            if (statusElement) statusElement.textContent = 'Gagal memuat preview gambar';
        }
    };

    window.previewBadgeIcon = function(event) {
        console.log('previewBadgeIcon called');
        const file = event.target.files[0];
        
        if (!file) {
            console.log('No file selected');
            return;
        }

        // Validate file type
        const allowedTypes = ['image/png', 'image/svg+xml', 'image/jpeg', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: 'Hanya file PNG, SVG, JPEG, dan WEBP yang diperbolehkan.',
                confirmButtonColor: '#dc2626'
            });
            event.target.value = '';
            return;
        }

        // Validate file size (2MB max)
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 2MB.',
                confirmButtonColor: '#dc2626'
            });
            event.target.value = '';
            return;
        }

        // Show loading state
        const previewContainer = document.getElementById('badgeIconPreview');
        const previewImg = document.getElementById('badgeIconPreviewImg');
        const statusElement = document.getElementById('badgeIconStatus');
        
        // Add loading indicator
        previewImg.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMTgiIHN0cm9rZT0iI2U1ZTdlYiIgc3Ryb2tlLXdpZHRoPSI0Ii8+CjxwYXRoIGQ9Im0zOCAyMGMwLTkuOTQxLTguMDU5LTE4LTE4LTE4IiBzdHJva2U9IiM2MzY2ZjEiIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIj4KPGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJyb3RhdGUiIGR1cj0iMXMiIHZhbHVlcz0iMCAyMCAyMDszNjAgMjAgMjAiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIi8+CjwvcGF0aD4KPC9zdmc+';
        previewContainer.classList.remove('hidden');
        if (statusElement) statusElement.textContent = 'Memuat preview icon...';
        
        // Hide current icon container
        const currentContainer = document.getElementById('currentBadgeIconContainer');
        if (currentContainer) currentContainer.classList.add('hidden');

        try {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    previewImg.src = e.target.result;
                    if (statusElement) statusElement.textContent = 'Preview icon berhasil dimuat';
                    console.log('Badge icon preview loaded successfully');
                } catch (error) {
                    console.error('Error setting preview icon:', error);
                    handlePreviewError();
                }
            };
            
            reader.onerror = function(error) {
                console.error('FileReader error:', error);
                handlePreviewError();
            };
            
            reader.readAsDataURL(file);
            
        } catch (error) {
            console.error('Error reading file:', error);
            handlePreviewError();
        }

        function handlePreviewError() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Preview',
                text: 'Terjadi kesalahan saat memuat preview icon. Silakan coba lagi.',
                confirmButtonColor: '#dc2626'
            });
            
            // Reset state
            event.target.value = '';
            previewContainer.classList.add('hidden');
            if (currentContainer) currentContainer.classList.remove('hidden');
            if (statusElement) statusElement.textContent = 'Gagal memuat preview icon';
        }
    };

    window.clearSectionImagePreview = function() {
        try {
            const fileInput = document.getElementById('section_image');
            const previewContainer = document.getElementById('sectionImagePreview');
            const previewImg = document.getElementById('sectionImagePreviewImg');
            const currentContainer = document.getElementById('currentSectionImageContainer');
            
            // Reset file input
            if (fileInput) fileInput.value = '';
            
            // Clear preview image source
            if (previewImg) previewImg.src = '';
            
            // Hide preview container
            if (previewContainer) previewContainer.classList.add('hidden');
            
            // Show current image container if exists
            if (currentContainer) currentContainer.classList.remove('hidden');
            
            console.log('Section image preview cleared successfully');
        } catch (error) {
            console.error('Error clearing section image preview:', error);
        }
    };

    window.clearBadgeIconPreview = function() {
        try {
            const fileInput = document.getElementById('badge_icon');
            const previewContainer = document.getElementById('badgeIconPreview');
            const previewImg = document.getElementById('badgeIconPreviewImg');
            const currentContainer = document.getElementById('currentBadgeIconContainer');
            
            // Reset file input
            if (fileInput) fileInput.value = '';
            
            // Clear preview image source
            if (previewImg) previewImg.src = '';
            
            // Hide preview container
            if (previewContainer) previewContainer.classList.add('hidden');
            
            // Show current icon container if exists
            if (currentContainer) currentContainer.classList.remove('hidden');
            
            console.log('Badge icon preview cleared successfully');
        } catch (error) {
            console.error('Error clearing badge icon preview:', error);
        }
    };

    window.removeCurrentSectionImage = function() {
        Swal.fire({
            title: 'Hapus Gambar Section?',
            text: 'Gambar section saat ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const container = document.getElementById('currentSectionImageContainer');
                if (container) container.remove();
                Swal.fire({
                    icon: 'success', 
                    title: 'Terhapus!', 
                    text: 'Gambar akan dihapus saat Anda menyimpan.', 
                    timer: 2000, 
                    showConfirmButton: false
                });
            }
        });
    }; '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const container = document.getElementById('currentSectionImageContainer');
                if (container) container.remove();
                Swal.fire({
                    icon: 'success', 
                    title: 'Terhapus!', 
                    text: 'Gambar akan dihapus saat Anda menyimpan.', 
                    timer: 2000, 
                    showConfirmButton: false
                });
            }
        });
    };

    window.removeCurrentBadgeIcon = function() {
        Swal.fire({
            title: 'Hapus Icon Badge?',
            text: 'Icon badge saat ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const container = document.getElementById('currentBadgeIconContainer');
                if (container) container.remove();
                Swal.fire({
                    icon: 'success', 
                    title: 'Terhapus!', 
                    text: 'Icon akan dihapus saat Anda menyimpan.', 
                    timer: 2000, 
                    showConfirmButton: false
                });
            }
        });
    };
    
    console.log('Functions defined:', {
        previewSectionImage: typeof window.previewSectionImage,
        previewBadgeIcon: typeof window.previewBadgeIcon,
        clearSectionImagePreview: typeof window.clearSectionImagePreview,
        clearBadgeIconPreview: typeof window.clearBadgeIconPreview,
        removeCurrentSectionImage: typeof window.removeCurrentSectionImage,
        removeCurrentBadgeIcon: typeof window.removeCurrentBadgeIcon
    });
})();
</script>

@if(session('success'))
<script>
Swal.fire({icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end'});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({icon: 'error', title: 'Gagal!', text: '{{ session("error") }}', confirmButtonColor: '#dc2626'});
</script>
@endif

@endsection
