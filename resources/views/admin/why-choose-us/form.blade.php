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

<x-admin.card>
    <form action="{{ $item->exists ? route('admin.why-choose-us.update', $item) : route('admin.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($item->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="col-span-1">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $item->title ?? '') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div class="col-span-1">
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Urutan <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="sort_order"
                       id="sort_order"
                       value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('sort_order') border-red-500 @enderror">
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="col-span-full">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon -->
            <div class="col-span-1">
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                    Icon (Format: PNG, SVG)
                </label>
                @if($item->exists && $item->icon)
                    <div class="mb-3">
                        <img src="{{ Storage::url($item->icon) }}" alt="Current icon" class="w-16 h-16 object-contain bg-gray-50 rounded-lg p-2 border border-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Icon saat ini</p>
                    </div>
                @endif
                <input type="file"
                       name="icon"
                       id="icon"
                       accept="image/png,image/svg+xml,image/jpeg,image/webp"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('icon') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Disarankan menggunakan icon SVG atau PNG transparan (Ukuran 64x64px)</p>
                @error('icon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Theme -->
            <div class="col-span-1">
                <label for="color_theme" class="block text-sm font-medium text-gray-700 mb-2">
                    Tema Warna <span class="text-red-500">*</span>
                </label>
                <select name="color_theme"
                        id="color_theme"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('color_theme') border-red-500 @enderror">
                    @foreach($themes as $value => $label)
                        <option value="{{ $value }}" {{ old('color_theme', $item->color_theme ?? 'primary') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Warna ini akan digunakan untuk background icon</p>
                @error('color_theme')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="col-span-full">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="sr-only peer"
                           {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <x-admin.button type="submit">
                {{ $item->exists ? 'Simpan Perubahan' : 'Simpan Data' }}
            </x-admin.button>
        </div>
    </form>
</x-admin.card>
@endsection
