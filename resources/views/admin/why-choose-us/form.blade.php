@extends('layouts.admin')

@section('title', isset($item) ? 'Edit Item' : 'Tambah Item')

@section('content')
<x-admin.page-header
    :title="isset($item) ? 'Edit Item' : 'Tambah Item'"
    :subtitle="isset($item) ? 'Edit data keunggulan' : 'Tambahkan data keunggulan baru'"
>
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.why-choose-us.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card>
    <form action="{{ isset($item) ? route('admin.why-choose-us.update', $item) : route('admin.why-choose-us.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="col-span-1">
                <x-admin.input name="title" label="Judul" :value="$item->title ?? ''" required />
            </div>

            <!-- Sort Order -->
             <div class="col-span-1">
                <x-admin.input type="number" name="sort_order" label="Urutan" :value="$item->sort_order ?? 0" required />
            </div>

            <!-- Description -->
            <div class="col-span-full">
                <x-admin.textarea name="description" label="Deskripsi" :value="$item->description ?? ''" required rows="3" />
            </div>

             <!-- Icon -->
            <div class="col-span-1">
                <x-admin.file-upload name="icon" label="Icon (Format: PNG, SVG)" :preview="$item->icon ?? null" />
                <p class="text-xs text-gray-500 mt-1">Disarankan menggunakan icon SVG atau PNG transparan (Ukuran 64x64px)</p>
            </div>

             <!-- Color Theme -->
            <div class="col-span-1">
                <x-admin.select name="color_theme" label="Tema Warna" :value="$item->color_theme ?? 'primary'" :options="$themes" required />
                <p class="text-xs text-gray-500 mt-1">Warna ini akan digunakan untuk background icon</p>
            </div>

             <!-- Is Active -->
            <div class="col-span-full">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ ($item->is_active ?? true) ? 'checked' : '' }}>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <x-admin.button type="submit">
                {{ isset($item) ? 'Simpan Perubahan' : 'Simpan Data' }}
            </x-admin.button>
        </div>
    </form>
</x-admin.card>
@endsection
