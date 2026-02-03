@extends('layouts.admin')

@section('title', 'Pengaturan Hero Slider')

@section('content')
<x-admin.page-header title="Pengaturan Hero Slider" subtitle="Konfigurasi slider banner di halaman utama">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.hero-slides.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card>
    <form action="{{ route('admin.hero-slides.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-xl">
            <x-admin.input 
                label="Delay Slider (ms)" 
                name="hero_slider_delay" 
                type="number" 
                :value="old('hero_slider_delay', $settings->hero_slider_delay ?? 5000)" 
                helper="Waktu tunggu sebelum slide berganti otomatis (dalam milidetik). Contoh: 5000 untuk 5 detik."
                required
                min="1000"
                max="20000"
            />
        </div>

        <div class="mt-6 flex items-center justify-end">
            <x-admin.button type="submit">
                Simpan Perubahan
            </x-admin.button>
        </div>
    </form>
</x-admin.card>
@endsection
