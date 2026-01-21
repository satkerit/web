@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<x-admin.page-header title="Edit Produk" :subtitle="$product->name">
    <x-slot:actions>
        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 bg-white rounded-xl ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat di Website
        </a>
        <x-admin.button href="{{ route('admin.products.index') }}" variant="secondary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" x-data="productForm()">
    @csrf
    @method('PUT')

    {{-- Error Messages --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <div class="flex items-center gap-2 font-semibold mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Terdapat kesalahan pada form:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <x-admin.card title="Informasi Dasar" subtitle="Data utama produk">
                <div class="space-y-5">
                    <x-admin.input
                        name="name"
                        label="Nama Produk"
                        :value="old('name', $product->name)"
                        required
                        placeholder="Contoh: Simpanan Wadiah"
                        :error="$errors->first('name')"
                    />

                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-0.5">
                            Tipe Produk <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all">
                            <option value="">Pilih Tipe Produk</option>
                            <option value="simpanan_syariah" {{ old('type', $product->type) == 'simpanan_syariah' ? 'selected' : '' }}>Simpanan Syariah</option>
                            <option value="pembiayaan_syariah" {{ old('type', $product->type) == 'pembiayaan_syariah' ? 'selected' : '' }}>Pembiayaan Syariah</option>
                            <option value="deposito_syariah" {{ old('type', $product->type) == 'deposito_syariah' ? 'selected' : '' }}>Deposito Syariah</option>
                        </select>
                        @error('type')
                            <p class="mt-1.5 text-xs text-red-600 font-medium ml-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-0.5">
                            Deskripsi Singkat
                        </label>
                        <textarea name="short_description" id="short_description" rows="2"
                                  placeholder="Ringkasan singkat tentang produk (maks 500 karakter)"
                                  class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all">{{ old('short_description', $product->short_description) }}</textarea>
                        <p class="mt-1.5 text-xs text-slate-500 ml-0.5">Akan ditampilkan di halaman daftar produk</p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-0.5">
                            Deskripsi Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="6" required
                                  placeholder="Jelaskan detail produk secara lengkap..."
                                  class="block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all @error('description') ring-red-300 focus:ring-red-500 bg-red-50/50 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs text-red-600 font-medium ml-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-admin.input
                        name="interest_rate"
                        label="Nisbah/Margin"
                        :value="old('interest_rate', $product->interest_rate)"
                        placeholder="Contoh: 3% - 5% per tahun"
                        hint="Informasi bagi hasil atau margin pembiayaan"
                    />
                </div>
            </x-admin.card>

            {{-- Features & Benefits --}}
            @php
                $featuresData = old('features', $product->features ?? []);
                $benefitsData = old('benefits', $product->benefits ?? []);
                $requirementsData = old('requirements', $product->requirements ?? []);

                if (!is_array($featuresData) || empty($featuresData)) $featuresData = [''];
                if (!is_array($benefitsData) || empty($benefitsData)) $benefitsData = [''];
                if (!is_array($requirementsData) || empty($requirementsData)) $requirementsData = [''];
            @endphp

            <x-admin.card title="Fitur & Keunggulan" subtitle="Tambahkan fitur, keunggulan, dan persyaratan produk">
                <div class="space-y-6">
                    {{-- Features --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Fitur Produk
                            </span>
                        </label>
                        <div x-data="repeaterField(@js(array_values($featuresData)))" class="space-y-2">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex gap-2 group">
                                    <input type="text" :name="'features['+index+']'" x-model="item.value"
                                           placeholder="Masukkan fitur produk"
                                           class="flex-1 rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all">
                                    <button type="button" @click="removeItem(index)"
                                            class="p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100"
                                            x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addItem()"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Fitur
                            </button>
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    {{-- Benefits --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Keunggulan Produk
                            </span>
                        </label>
                        <div x-data="repeaterField(@js(array_values($benefitsData)))" class="space-y-2">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex gap-2 group">
                                    <input type="text" :name="'benefits['+index+']'" x-model="item.value"
                                           placeholder="Masukkan keunggulan produk"
                                           class="flex-1 rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all">
                                    <button type="button" @click="removeItem(index)"
                                            class="p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100"
                                            x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addItem()"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Keunggulan
                            </button>
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    {{-- Requirements --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Persyaratan
                            </span>
                        </label>
                        <div x-data="repeaterField(@js(array_values($requirementsData)))" class="space-y-2">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex gap-2 group">
                                    <input type="text" :name="'requirements['+index+']'" x-model="item.value"
                                           placeholder="Masukkan persyaratan"
                                           class="flex-1 rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 hover:ring-slate-300 transition-all">
                                    <button type="button" @click="removeItem(index)"
                                            class="p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100"
                                            x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addItem()"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 hover:text-emerald-700 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Persyaratan
                            </button>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Product Info Card --}}
            <x-admin.card>
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl mb-4">
                    @if($product->image)
                        <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->image_alt ?? $product->name }}" class="w-16 h-16 rounded-xl object-cover ring-1 ring-slate-200">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-slate-500">Dibuat: {{ $product->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-slate-500">Diupdate: {{ $product->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    <p>Slug: <code class="bg-slate-100 px-1.5 py-0.5 rounded">{{ $product->slug }}</code></p>
                </div>
            </x-admin.card>

            {{-- Status & Order --}}
            <x-admin.card title="Publikasi" subtitle="Pengaturan tampilan produk">
                <div class="space-y-5">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div>
                            <label for="is_active" class="text-sm font-semibold text-slate-700">Status Aktif</label>
                            <p class="text-xs text-slate-500 mt-0.5">Tampilkan produk di website</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <x-admin.input
                        type="number"
                        name="order_position"
                        label="Urutan Tampil"
                        :value="old('order_position', $product->order_position)"
                        min="0"
                        hint="Angka lebih kecil ditampilkan lebih dulu"
                    />
                </div>
            </x-admin.card>

            {{-- Product Image --}}
            <x-admin.card title="Gambar Produk" subtitle="Upload gambar untuk produk">
                <div class="space-y-4">
                    <x-admin.image-picker
                        name="image"
                        :value="$product->image"
                        hint="Format: JPG, PNG, WebP. Maksimal 5MB. Rasio 16:9 disarankan."
                        previewClass="w-full h-44 object-cover rounded-lg"
                    />

                    <x-admin.input
                        name="image_alt"
                        label="Alt Text Gambar"
                        :value="old('image_alt', $product->image_alt)"
                        placeholder="Deskripsi gambar untuk SEO"
                        hint="Membantu aksesibilitas dan SEO"
                    />
                </div>
            </x-admin.card>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <x-admin.button type="submit" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </x-admin.button>

                <button type="button" onclick="confirmDelete()" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Produk
                </button>
            </div>
        </div>
    </div>
</form>

{{-- Delete Form --}}
<form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- Delete Confirmation Modal --}}
<x-admin.delete-modal
    id="deleteModal"
    title="Hapus Produk"
    :message="'Apakah Anda yakin ingin menghapus produk \'' . $product->name . '\'? Tindakan ini tidak dapat dibatalkan.'"
/>

@push('styles')
<script>
    // Define productForm before Alpine initializes
    document.addEventListener('alpine:init', () => {
        Alpine.data('productForm', () => ({
            // Add any form-specific logic here
        }));
    });
</script>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        const modal = document.getElementById('deleteModal');
        const form = modal.querySelector('form');

        form.action = '{{ route('admin.products.destroy', $product) }}';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endpush
@endsection
