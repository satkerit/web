@extends('layouts.admin')

@section('title', $item->exists ? 'Edit Item' : 'Tambah Item')

@section('content')
<x-admin.page-header
    :title="$item->exists ? 'Edit Item' : 'Tambah Item'"
    :subtitle="$item->exists ? 'Edit data keunggulan' : 'Tambahkan data keunggulan baru'"
>
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.why-choose-us.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ $item->exists ? route('admin.why-choose-us.update', $item) : route('admin.why-choose-us.store') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      id="whyChooseUsForm">
    @csrf
    @if($item->exists)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <x-admin.card title="Informasi Dasar" subtitle="Data utama keunggulan">
                <div class="space-y-5">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title', $item->title ?? '') }}"
                               required
                               placeholder="Contoh: Pelayanan Terbaik"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  required
                                  placeholder="Jelaskan keunggulan ini secara detail..."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none @error('description') border-red-500 @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>

            {{-- Icon --}}
            <x-admin.card title="Icon" subtitle="Upload icon untuk item ini">
                <div>
                    @if($item->exists && $item->icon)
                        <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200" id="currentIconContainer">
                            <img src="{{ \App\Helpers\StorageHelper::url($item->icon) }}" 
                                 alt="Current icon" 
                                 class="w-20 h-20 object-contain mx-auto"
                                 id="currentIcon">
                            <p class="text-xs text-gray-500 text-center mt-2">Icon saat ini</p>
                            <button type="button" 
                                    onclick="removeCurrentIcon()"
                                    class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                                Hapus Icon
                            </button>
                        </div>
                    @endif

                    <div class="relative">
                        <input type="file"
                               name="icon"
                               id="icon"
                               accept="image/png,image/svg+xml,image/jpeg,image/webp"
                               class="hidden"
                               onchange="previewIcon(event)">
                        <label for="icon" 
                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 hover:bg-primary-50 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-xs text-gray-500 font-medium">Upload Icon</p>
                                <p class="text-xs text-gray-400">PNG, SVG, JPG (Max 2MB)</p>
                            </div>
                        </label>
                    </div>

                    <!-- Icon Preview -->
                    <div id="iconPreview" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="" alt="Icon preview" class="w-20 h-20 object-contain mx-auto" id="iconPreviewImg">
                        <p class="text-xs text-gray-500 text-center mt-2">Preview Icon</p>
                        <button type="button" 
                                onclick="clearIconPreview()"
                                class="mt-2 w-full text-xs text-red-600 hover:text-red-700 font-medium">
                            Batal
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        <strong>Rekomendasi:</strong> Icon SVG atau PNG transparan (64x64px)
                    </p>
                    @error('icon')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Settings --}}
            <x-admin.card title="Pengaturan" subtitle="Konfigurasi tampilan">
                <div class="space-y-5">
                    <!-- Color Theme -->
                    <div>
                        <label for="color_theme" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tema Warna <span class="text-red-500">*</span>
                        </label>
                        <select name="color_theme"
                                id="color_theme"
                                required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('color_theme') border-red-500 @enderror">
                            @foreach($themes as $value => $label)
                                <option value="{{ $value }}" {{ old('color_theme', $item->color_theme ?? 'primary') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5">Warna untuk background icon</p>
                        @error('color_theme')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">
                            Urutan Tampil <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                               required
                               min="0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('sort_order') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1.5">Semakin kecil, semakin awal ditampilkan</p>
                        @error('sort_order')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="pt-2">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">Status Aktif</span>
                                <p class="text-xs text-gray-500 mt-0.5">Tampilkan di frontend</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       class="sr-only peer"
                                       {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </div>
                        </label>
                    </div>
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
                        {{ $item->exists ? 'Simpan Perubahan' : 'Simpan Data' }}
                    </button>
                    
                    <a href="{{ route('admin.why-choose-us.index') }}" 
                       class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                </div>
            </x-admin.card>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    'use strict';
    
    console.log('Why Choose Us Form Script Loaded');
    
    // Icon Preview
    window.previewIcon = function(event) {
        console.log('previewIcon called');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('iconPreviewImg').src = e.target.result;
                document.getElementById('iconPreview').classList.remove('hidden');
                
                const currentContainer = document.getElementById('currentIconContainer');
                if (currentContainer) {
                    currentContainer.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    };

    window.clearIconPreview = function() {
        document.getElementById('icon').value = '';
        document.getElementById('iconPreview').classList.add('hidden');
        
        const currentContainer = document.getElementById('currentIconContainer');
        if (currentContainer) {
            currentContainer.classList.remove('hidden');
        }
    };

    window.removeCurrentIcon = function() {
        Swal.fire({
            title: 'Hapus Icon?',
            text: 'Icon saat ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const container = document.getElementById('currentIconContainer');
                if (container) {
                    container.remove();
                }
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
        previewIcon: typeof window.previewIcon,
        clearIconPreview: typeof window.clearIconPreview,
        removeCurrentIcon: typeof window.removeCurrentIcon
    });
})();
</script>
@endpush
