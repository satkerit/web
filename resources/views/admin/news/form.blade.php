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
</style>
@endpush

@section('content')
<x-admin.page-header :title="isset($news) ? 'Edit Berita' : 'Tambah Berita'" subtitle="Kelola konten berita">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.news.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if ($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <h4 class="text-red-800 font-medium mb-2">Terjadi kesalahan:</h4>
    <ul class="list-disc list-inside text-sm text-red-700">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
    <p class="text-red-700">{{ session('error') }}</p>
</div>
@endif

<form id="news-form" action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($news)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Konten Berita">
                <div class="space-y-4">
                    <x-admin.input name="title" label="Judul" :value="old('title', $news->title ?? '')" required :error="$errors->first('title')"/>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                        <textarea name="content" id="summernote">{{ old('content', $news->content ?? '') }}</textarea>
                        @error('content')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                        <textarea name="excerpt" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Ringkasan singkat untuk preview (opsional)</p>
                    </div>
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publikasi">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Berita" {{ old('category', $news->category ?? '') == 'Berita' ? 'selected' : '' }}>Berita</option>
                            <option value="Artikel" {{ old('category', $news->category ?? '') == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                            <option value="Pengumuman" {{ old('category', $news->category ?? '') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Promo" {{ old('category', $news->category ?? '') == 'Promo' ? 'selected' : '' }}>Promo</option>
                        </select>
                        @error('category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <x-admin.input type="datetime-local" name="published_at" label="Tanggal Publikasi" :value="old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'))" :error="$errors->first('published_at')"/>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="is_published" class="text-sm text-gray-700">Publikasikan</label>
                    </div>
                </div>
            </x-admin.card>
            <x-admin.card title="Gambar Utama">
                <div class="space-y-3">
                    @if(isset($news) && $news->featured_image)
                    <div class="relative">
                        <img src="{{ Storage::url($news->featured_image) }}" alt="Featured Image" class="w-full h-40 object-cover rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                    </div>
                    @endif
                    <input type="file" name="featured_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-500">Format: JPG, PNG, WebP. Maks 2MB</p>
                    @error('featured_image')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-admin.card>
            <x-admin.card title="Slide Show (Max 3)">
                <div class="space-y-3">
                    @if(isset($news) && $news->images->count() > 0)
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        @foreach($news->images as $image)
                        <div class="relative group">
                            <img src="{{ Storage::url($image->image_path) }}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="if(confirm('Hapus slide ini?')) document.getElementById('delete-image-{{ $image->id }}').submit();" class="absolute top-1 right-1 bg-red-600/90 text-white p-1 rounded-full hover:bg-red-700 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500">{{ $news->images->count() }}/3 slide terpakai</p>
                    @endif
                    @if(!isset($news) || $news->images->count() < 3)
                    <input type="file" name="slide_images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-500">Maksimal {{ isset($news) ? 3 - $news->images->count() : 3 }} foto lagi.</p>
                    @error('slide_images')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    @else
                    <div class="p-3 bg-yellow-50 text-yellow-800 text-xs rounded-lg border border-yellow-200">Maksimal 3 slide tercapai.</div>
                    @endif
                </div>
            </x-admin.card>
            <x-admin.button type="submit" class="w-full">{{ isset($news) ? 'Simpan Perubahan' : 'Tambah Berita' }}</x-admin.button>
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
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
        callbacks: { onInit: function() { console.log('Summernote ready!'); } }
    });
});
</script>
@endpush
