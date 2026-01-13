@extends('layouts.admin')

@section('title', isset($office) ? 'Edit Kantor' : 'Tambah Kantor')

@section('content')
<x-admin.page-header :title="isset($office) ? 'Edit Kantor' : 'Tambah Kantor'">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.offices.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ isset($office) ? route('admin.offices.update', $office) : route('admin.offices.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($office)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Informasi Kantor">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.input name="name" label="Nama Kantor" :value="old('name', $office->name ?? '')" required :error="$errors->first('name')"/>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kantor <span class="text-red-500">*</span></label>
                            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="pusat" {{ old('type', $office->type ?? '') == 'pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                                <option value="cabang" {{ old('type', $office->type ?? '') == 'cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                                <option value="kas" {{ old('type', $office->type ?? '') == 'kas' ? 'selected' : '' }}>Kantor Kas</option>
                                <option value="kas_keliling" {{ old('type', $office->type ?? '') == 'kas_keliling' ? 'selected' : '' }}>Kas Keliling</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>{{ old('address', $office->address ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('description', $office->description ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.input name="phone" label="Telepon" :value="old('phone', $office->phone ?? '')"/>
                        <x-admin.input type="email" name="email" label="Email" :value="old('email', $office->email ?? '')"/>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Lokasi" x-data="mapPicker()">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="number" name="latitude" id="latitude" x-model="latitude" @input="updateMap()"
                                   value="{{ old('latitude', $office->latitude ?? '') }}" step="any" placeholder="-2.123456"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Contoh: -2.131629</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="number" name="longitude" id="longitude" x-model="longitude" @input="updateMap()"
                                   value="{{ old('longitude', $office->longitude ?? '') }}" step="any" placeholder="106.123456"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Contoh: 106.116504</p>
                        </div>
                    </div>

                    <!-- Map Preview -->
                    <div x-show="hasCoordinates" x-transition class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Preview Lokasi</label>
                        <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-100" style="height: 300px;">
                            <iframe
                                x-ref="mapFrame"
                                :src="mapUrl"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <a :href="directionsUrl" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Buka di Google Maps
                            </a>
                            <span class="text-gray-400">|</span>
                            <span class="text-gray-500" x-text="'Koordinat: ' + latitude + ', ' + longitude"></span>
                        </div>
                    </div>

                    <!-- Help text when no coordinates -->
                    <div x-show="!hasCoordinates" class="p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Cara mendapatkan koordinat:</p>
                                <ol class="text-sm text-gray-500 mt-1 list-decimal list-inside space-y-1">
                                    <li>Buka <a href="https://www.google.com/maps" target="_blank" class="text-emerald-600 hover:underline">Google Maps</a></li>
                                    <li>Cari lokasi kantor</li>
                                    <li>Klik kanan pada lokasi</li>
                                    <li>Klik koordinat yang muncul untuk menyalin</li>
                                    <li>Paste di kolom Latitude dan Longitude</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Status">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $office->is_active ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_active" class="text-sm text-gray-700">Kantor Aktif</label>
                </div>
            </x-admin.card>

            <x-admin.card title="Foto Kantor">
                <x-admin.image-picker
                    name="photo"
                    :value="$office->photo ?? null"
                    hint="Format: JPG, PNG, WebP. Maks 2MB"
                    previewClass="w-full h-40 object-cover"
                />
            </x-admin.card>

            <x-admin.button type="submit" class="w-full">
                {{ isset($office) ? 'Simpan Perubahan' : 'Tambah Kantor' }}
            </x-admin.button>
        </div>
    </div>
</form>

<script>
function mapPicker() {
    return {
        latitude: '{{ old('latitude', $office->latitude ?? '') }}',
        longitude: '{{ old('longitude', $office->longitude ?? '') }}',

        get hasCoordinates() {
            return this.latitude && this.longitude &&
                   !isNaN(parseFloat(this.latitude)) &&
                   !isNaN(parseFloat(this.longitude));
        },

        get mapUrl() {
            if (!this.hasCoordinates) return '';
            const lat = parseFloat(this.latitude);
            const lng = parseFloat(this.longitude);
            return `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000!2d${lng}!3d${lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sid!2sid!4v1234567890!5m2!1sid!2sid&markers=color:red%7C${lat},${lng}`;
        },

        get directionsUrl() {
            if (!this.hasCoordinates) return '#';
            return `https://www.google.com/maps/dir/?api=1&destination=${this.latitude},${this.longitude}`;
        },

        updateMap() {
            // Map updates automatically via Alpine.js reactivity
        }
    }
}
</script>
@endsection
