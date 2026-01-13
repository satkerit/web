@extends('layouts.admin')

@section('title', isset($heroSlide) ? 'Edit Slide' : 'Tambah Slide')

@section('content')
<x-admin.page-header :title="isset($heroSlide) ? 'Edit Slide' : 'Tambah Slide'">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.hero-slides.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ isset($heroSlide) ? route('admin.hero-slides.update', $heroSlide) : route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($heroSlide)) @method('PUT') @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Konten Slide">
                <div class="space-y-4">
                    <x-admin.input name="title" label="Judul" :value="old('title', $heroSlide->title ?? '')" hint="Opsional"/>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <textarea name="subtitle" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('subtitle', $heroSlide->subtitle ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.input type="text" name="link_url" label="URL Link" :value="old('link_url', $heroSlide->link_url ?? '')" placeholder="https://..."/>
                        <x-admin.input name="link_text" label="Teks Tombol" :value="old('link_text', $heroSlide->link_text ?? '')" placeholder="Selengkapnya"/>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Gambar Slide">
                <div class="space-y-4">
                    <x-admin.image-picker
                        name="image"
                        :value="$heroSlide->image ?? null"
                        :required="!isset($heroSlide)"
                        hint="Rekomendasi: 1920x600px. Format: JPG, PNG, WebP. Maks 5MB"
                        previewClass="w-full h-48 object-cover"
                    />
                    @error('image')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Pengaturan">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $heroSlide->is_active ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="is_active" class="text-sm text-gray-700">Aktif</label>
                    </div>

                    <x-admin.input type="number" name="order_position" label="Urutan" :value="old('order_position', $heroSlide->order_position ?? 0)" min="0"/>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Transisi</label>
                        <select name="transition_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            @foreach($transitionTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('transition_type', $heroSlide->transition_type ?? 'slide') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-admin.input type="number" name="transition_duration" label="Durasi Transisi (ms)" :value="old('transition_duration', $heroSlide->transition_duration ?? 500)" min="100" max="10000"/>
                </div>
            </x-admin.card>

            <x-admin.card title="Tampilkan">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="show_title" id="show_title" value="1"
                               {{ old('show_title', $heroSlide->show_title ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="show_title" class="text-sm text-gray-700">Tampilkan Judul</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="show_subtitle" id="show_subtitle" value="1"
                               {{ old('show_subtitle', $heroSlide->show_subtitle ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="show_subtitle" class="text-sm text-gray-700">Tampilkan Subtitle</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="show_button" id="show_button" value="1"
                               {{ old('show_button', $heroSlide->show_button ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="show_button" class="text-sm text-gray-700">Tampilkan Tombol</label>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full">
                {{ isset($heroSlide) ? 'Simpan Perubahan' : 'Tambah Slide' }}
            </x-admin.button>
        </div>
    </div>
</form>
@endsection
