@extends('layouts.admin')

@section('title', isset($news) ? 'Edit Berita' : 'Tambah Berita')

@push('styles')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
.note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; }
.note-editor .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #d1d5db !important; border-radius: 0.5rem 0.5rem 0 0 !important; padding: 8px !important; }
.note-editor .note-editing-area { min-height: 350px !important; }
.note-editor .note-editing-area .note-editable { padding: 15px !important; font-size: 14px !important; line-height: 1.6 !important; }
.note-editor .note-statusbar { border-radius: 0 0 0.5rem 0.5rem !important; background: #f9fafb !important; }
.note-btn { border-radius: 4px !important; }
.note-btn:hover { background-color: #d1fae5 !important; }
.note-btn.active { background-color: #10b981 !important; color: white !important; }

/* Image Preview Styles */
.image-preview {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
    transition: all 0.3s ease;
}
.image-preview:hover {
    border-color: #10b981;
    background: #f0fdf4;
}
.image-preview.has-image {
    border: 2px solid #10b981;
    background: white;
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview .placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: #6b7280;
}
.image-preview .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.image-preview:hover .overlay {
    opacity: 1;
}
.slide-preview {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 0.5rem;
    overflow: hidden;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
}
.slide-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.slide-preview .remove-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.slide-preview:hover .remove-btn {
    opacity: 1;
}
</style>
@endpush

@section('content')
<x-admin.page-header :title="isset($news) ? 'Edit Berita' : 'Tambah Berita'" subtitle="Kelola konten berita dan artikel">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.news.index') }}" variant="secondary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if ($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h4 class="text-red-800 font-medium mb-2">Terjadi kesalahan:</h4>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<form id="news-form" action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($news)) @method('PUT') @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Basic Information -->
            <x-admin.card title="Informasi Dasar" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" 
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-base"
                               placeholder="Masukkan judul berita yang menarik..." required>
                        @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Ringkasan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <textarea name="excerpt" rows="3" 
                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-base"
                                  placeholder="Ringkasan singkat untuk preview di halaman utama...">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                        <p class="mt-2 text-sm text-gray-500">Ringkasan akan ditampilkan di halaman daftar berita</p>
                        @error('excerpt')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-admin.card>

            <!-- Content Editor -->
            <x-admin.card title="Konten Berita" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Isi Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="summernote">{{ old('content', $news->content ?? '') }}</textarea>
                    @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-admin.card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publication Settings -->
            <x-admin.card title="Pengaturan Publikasi" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h8m-8 0V7a2 2 0 012-2h4a2 2 0 012 2v4"/>'>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-base">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Berita" {{ old('category', $news->category ?? '') == 'Berita' ? 'selected' : '' }}>📰 Berita</option>
                            <option value="Artikel" {{ old('category', $news->category ?? '') == 'Artikel' ? 'selected' : '' }}>📝 Artikel</option>
                            <option value="Pengumuman" {{ old('category', $news->category ?? '') == 'Pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                            <option value="Promo" {{ old('category', $news->category ?? '') == 'Promo' ? 'selected' : '' }}>🎉 Promo</option>
                        </select>
                        @error('category')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Publikasi
                        </label>
                        <input type="datetime-local" name="published_at" 
                               value="{{ old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-base">
                        @error('published_at')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <input type="checkbox" name="is_published" id="is_published" value="1" 
                               {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }} 
                               class="rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 mr-3">
                        <label for="is_published" class="text-sm font-medium text-emerald-800">
                            <span class="block">Publikasikan Sekarang</span>
                            <span class="text-emerald-600 text-xs">Berita akan langsung tampil di website</span>
                        </label>
                    </div>
                </div>
            </x-admin.card>

            <!-- Featured Image -->
            <x-admin.card title="Gambar Utama" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'>
                <div class="space-y-4">
                    <div class="image-preview {{ isset($news) && $news->featured_image ? 'has-image' : '' }}" style="aspect-ratio: 16/9;">
                        @if(isset($news) && $news->featured_image)
                            <img src="{{ \App\Helpers\StorageHelper::url($news->featured_image) }}" alt="Featured Image" id="featured-preview">
                            <div class="overlay">
                                <button type="button" onclick="document.getElementById('featured_image').click()" class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                    Ganti Gambar
                                </button>
                            </div>
                        @else
                            <div class="placeholder">
                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium mb-1">Klik untuk upload gambar</p>
                                <p class="text-xs">JPG, PNG, WebP (Max 2MB)</p>
                            </div>
                        @endif
                    </div>
                    
                    <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden" onchange="previewFeaturedImage(this)">
                    
                    <button type="button" onclick="document.getElementById('featured_image').click()" 
                            class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        {{ isset($news) && $news->featured_image ? 'Ganti Gambar Utama' : 'Upload Gambar Utama' }}
                    </button>
                    
                    @error('featured_image')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-admin.card>

            <!-- Image Gallery -->
            <x-admin.card title="Galeri Gambar" subtitle="Maksimal 3 gambar tambahan" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'>
                <div class="space-y-4">
                    @if(isset($news) && $news->images->count() > 0)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($news->images as $image)
                        <div class="slide-preview">
                            <img src="{{ \App\Helpers\StorageHelper::url($image->image_path) }}" alt="Slide {{ $loop->iteration }}">
                            <button type="button" class="remove-btn" onclick="if(confirm('Hapus gambar ini?')) document.getElementById('delete-image-{{ $image->id }}').submit();">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <p class="text-sm text-blue-800 font-medium">{{ $news->images->count() }}/3 gambar terpakai</p>
                    </div>
                    @endif

                    @if(!isset($news) || $news->images->count() < 3)
                    <div>
                        <input type="file" name="slide_images[]" id="slide_images" multiple accept="image/*" class="hidden" onchange="previewSlideImages(this)">
                        <button type="button" onclick="document.getElementById('slide_images').click()" 
                                class="w-full py-4 px-4 border-2 border-dashed border-gray-300 hover:border-emerald-500 rounded-xl text-gray-600 hover:text-emerald-600 transition-colors">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <p class="font-medium">Tambah Gambar ke Galeri</p>
                            <p class="text-sm text-gray-500 mt-1">
                                Maksimal {{ isset($news) ? 3 - $news->images->count() : 3 }} gambar lagi
                            </p>
                        </button>
                        @error('slide_images')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    @else
                    <div class="text-center p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-yellow-800 font-medium">Maksimal 3 gambar tercapai</p>
                        <p class="text-xs text-yellow-600 mt-1">Hapus gambar yang ada untuk menambah yang baru</p>
                    </div>
                    @endif
                </div>
            </x-admin.card>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <x-admin.button type="submit" class="w-full py-4 text-base font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($news) ? 'Simpan Perubahan' : 'Publikasikan Berita' }}
                </x-admin.button>
                
                @if(isset($news))
                <a href="{{ route('news.show', $news->slug) }}" target="_blank" 
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat di Website
                </a>
                @endif
            </div>
        </div>
    </div>
</form>

@if(isset($news))
@foreach($news->images as $image)
<form id="delete-image-{{ $image->id }}" action="{{ route('admin.news.delete-image', $image) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
@endforeach
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
jQuery(function($) {
    $('#summernote').summernote({
        placeholder: 'Tulis konten berita di sini...',
        tabsize: 2,
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        styleTags: ['p', 'h2', 'h3', 'h4', 'h5', 'blockquote'],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48'],
        callbacks: {
            onInit: function() {
                console.log('Summernote ready!');
            }
        }
    });
});

function previewFeaturedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.image-preview');
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Featured Image Preview" id="featured-preview">
                <div class="overlay">
                    <button type="button" onclick="document.getElementById('featured_image').click()" class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                        Ganti Gambar
                    </button>
                </div>
            `;
            preview.classList.add('has-image');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewSlideImages(input) {
    // This is a placeholder for slide image preview functionality
    // You can implement this if needed
    console.log('Slide images selected:', input.files.length);
}
</script>
@endpush
