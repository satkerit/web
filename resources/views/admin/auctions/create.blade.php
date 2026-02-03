@extends('layouts.admin')

@section('title', 'Tambah Lelang Agunan')

@section('content')
<x-admin.page-header title="Tambah Lelang Agunan" subtitle="Buat lelang agunan baru dengan informasi lengkap">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.auctions.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('admin.auctions.store') }}" enctype="multipart/form-data" id="auction-form">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <x-admin.card title="Informasi Dasar" subtitle="Data utama lelang agunan">
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul Lelang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('title') border-red-500 @enderror"
                                   required placeholder="Contoh: Rumah Mewah 2 Lantai di Pangkalpinang">
                            @error('title')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Lelang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="auction_number" id="auction_number" value="{{ old('auction_number') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('auction_number') border-red-500 @enderror"
                                   required placeholder="Contoh: LA-2026-001">
                            @error('auction_number')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="asset_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Aset <span class="text-red-500">*</span>
                            </label>
                            <select name="asset_type" id="asset_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('asset_type') border-red-500 @enderror" required>
                                <option value="">Pilih Jenis Aset</option>
                                @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('asset_type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_type')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('city') border-red-500 @enderror"
                                   placeholder="Contoh: Pangkalpinang">
                            @error('city')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('description') border-red-500 @enderror"
                                  placeholder="Deskripsi detail tentang aset yang dilelang...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>

            {{-- Object Information --}}
            <x-admin.card title="Informasi Objek" subtitle="Detail spesifikasi objek lelang">
                <div class="space-y-5">
                    {{-- Certificate Information --}}
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">Informasi Sertifikat</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="certificate_type" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Sertifikat</label>
                                <select name="certificate_type" id="certificate_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('certificate_type') border-red-500 @enderror">
                                    <option value="">Pilih Jenis Sertifikat</option>
                                    @foreach(\App\Models\Auction::$certificateTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('certificate_type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('certificate_type')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Sertifikat</label>
                                <input type="text" name="certificate_number" id="certificate_number" value="{{ old('certificate_number') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('certificate_number') border-red-500 @enderror"
                                       placeholder="Contoh: 12345/2023">
                                @error('certificate_number')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Property Details --}}
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <h4 class="text-sm font-semibold text-green-900 mb-3">Detail Properti</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="land_area" class="block text-sm font-semibold text-gray-700 mb-2">Luas Tanah (m²)</label>
                                <input type="number" name="land_area" id="land_area" value="{{ old('land_area') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('land_area') border-red-500 @enderror"
                                       step="0.01" placeholder="120">
                                @error('land_area')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="building_area" class="block text-sm font-semibold text-gray-700 mb-2">Luas Bangunan (m²)</label>
                                <input type="number" name="building_area" id="building_area" value="{{ old('building_area') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('building_area') border-red-500 @enderror"
                                       step="0.01" placeholder="80">
                                @error('building_area')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year_built" class="block text-sm font-semibold text-gray-700 mb-2">Tahun Dibangun</label>
                                <input type="number" name="year_built" id="year_built" value="{{ old('year_built') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('year_built') border-red-500 @enderror"
                                       min="1900" max="{{ date('Y') }}" placeholder="2020">
                                @error('year_built')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label for="floors" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Lantai</label>
                                <input type="number" name="floors" id="floors" value="{{ old('floors') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('floors') border-red-500 @enderror"
                                       min="1" placeholder="2">
                                @error('floors')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bedrooms" class="block text-sm font-semibold text-gray-700 mb-2">Kamar Tidur</label>
                                <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('bedrooms') border-red-500 @enderror"
                                       min="0" placeholder="3">
                                @error('bedrooms')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bathrooms" class="block text-sm font-semibold text-gray-700 mb-2">Kamar Mandi</label>
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('bathrooms') border-red-500 @enderror"
                                       min="0" placeholder="2">
                                @error('bathrooms')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="parking_spaces" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Parkir</label>
                                <input type="number" name="parking_spaces" id="parking_spaces" value="{{ old('parking_spaces') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('parking_spaces') border-red-500 @enderror"
                                       min="0" placeholder="1">
                                @error('parking_spaces')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="building_condition" class="block text-sm font-semibold text-gray-700 mb-2">Kondisi Bangunan</label>
                            <select name="building_condition" id="building_condition" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('building_condition') border-red-500 @enderror">
                                <option value="">Pilih Kondisi Bangunan</option>
                                <option value="sangat_baik" {{ old('building_condition') === 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                <option value="baik" {{ old('building_condition') === 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="cukup" {{ old('building_condition') === 'cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="perlu_renovasi" {{ old('building_condition') === 'perlu_renovasi' ? 'selected' : '' }}>Perlu Renovasi</option>
                                <option value="rusak" {{ old('building_condition') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            @error('building_condition')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Facilities --}}
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <h4 class="text-sm font-semibold text-purple-900 mb-3">Fasilitas & Akses</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="facilities" class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas</label>
                                <textarea name="facilities" id="facilities" rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('facilities') border-red-500 @enderror"
                                          placeholder="Contoh: Listrik PLN, Air PDAM, Telepon, Internet, Taman, Pagar, dll">{{ old('facilities') }}</textarea>
                                @error('facilities')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nearby_facilities" class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas Sekitar</label>
                                <textarea name="nearby_facilities" id="nearby_facilities" rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('nearby_facilities') border-red-500 @enderror"
                                          placeholder="Contoh: Sekolah, Rumah Sakit, Mall, Pasar, Masjid, dll">{{ old('nearby_facilities') }}</textarea>
                                @error('nearby_facilities')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="transportation_access" class="block text-sm font-semibold text-gray-700 mb-2">Akses Transportasi</label>
                                <textarea name="transportation_access" id="transportation_access" rows="2"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('transportation_access') border-red-500 @enderror"
                                          placeholder="Contoh: 5 menit ke jalan raya, 10 menit ke terminal, dll">{{ old('transportation_access') }}</textarea>
                                @error('transportation_access')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- Address Information --}}
            <x-admin.card title="Informasi Alamat" subtitle="Lokasi aset yang dilelang">
                <div class="space-y-5">
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('address') border-red-500 @enderror"
                                  required placeholder="Alamat lengkap aset...">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="village" class="block text-sm font-semibold text-gray-700 mb-2">Kelurahan/Desa</label>
                            <input type="text" name="village" id="village" value="{{ old('village') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('village') border-red-500 @enderror">
                            @error('village')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                            <input type="text" name="district" id="district" value="{{ old('district') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('district') border-red-500 @enderror">
                            @error('district')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                            <input type="text" name="province" id="province" value="{{ old('province') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('province') border-red-500 @enderror">
                            @error('province')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- Pricing Information --}}
            <x-admin.card title="Informasi Harga" subtitle="Detail harga dan biaya lelang">
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="limit_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                Harga Limit
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="limit_price" id="limit_price" value="{{ old('limit_price') }}"
                                       class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('limit_price') border-red-500 @enderror"
                                       placeholder="850000000">
                            </div>
                            @error('limit_price')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estimated_price" class="block text-sm font-semibold text-gray-700 mb-2">Harga Taksiran</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="estimated_price" id="estimated_price" value="{{ old('estimated_price') }}"
                                       class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('estimated_price') border-red-500 @enderror"
                                       placeholder="1000000000">
                            </div>
                            @error('estimated_price')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="deposit_amount" class="block text-sm font-semibold text-gray-700 mb-2">Uang Jaminan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount') }}"
                                       class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('deposit_amount') border-red-500 @enderror"
                                       placeholder="170000000">
                            </div>
                            @error('deposit_amount')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deposit_percentage" class="block text-sm font-semibold text-gray-700 mb-2">Persentase Jaminan (%)</label>
                            <input type="number" name="deposit_percentage" id="deposit_percentage" value="{{ old('deposit_percentage', 20) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('deposit_percentage') border-red-500 @enderror"
                                   min="0" max="100" step="0.01" placeholder="20">
                            @error('deposit_percentage')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </x-admin.card>

            {{-- Auction Information --}}
            <x-admin.card title="Informasi Lelang" subtitle="Detail pelaksanaan lelang">
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="auction_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal & Waktu Lelang
                            </label>
                            <input type="datetime-local" name="auction_date" id="auction_date" value="{{ old('auction_date') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('auction_date') border-red-500 @enderror">
                            @error('auction_date')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Lelang <span class="text-red-500">*</span>
                            </label>
                            <select name="auction_type" id="auction_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('auction_type') border-red-500 @enderror" required>
                                <option value="">Pilih Jenis Lelang</option>
                                @foreach(\App\Models\Auction::$auctionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('auction_type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('auction_type')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="auction_location" class="block text-sm font-semibold text-gray-700 mb-2">
                            Lokasi Lelang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="auction_location" id="auction_location" value="{{ old('auction_location') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('auction_location') border-red-500 @enderror"
                               required placeholder="Contoh: Kantor BPRS Bangka Belitung">
                        @error('auction_location')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>

            {{-- Contact Information --}}
            <x-admin.card title="Informasi Kontak" subtitle="Detail kontak person lelang">
                <div x-data="{
                    contacts: {{ json_encode(old('contacts', [['name' => '', 'phone' => '']])) }}
                }" class="space-y-5">
                    <template x-for="(contact, index) in contacts" :key="index">
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 relative">
                            <button type="button" @click="contacts.splice(index, 1)" x-show="contacts.length > 1" class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label :for="'contact_name_' + index" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kontak Person
                                    </label>
                                    <input type="text" :name="'contacts[' + index + '][name]'" :id="'contact_name_' + index" x-model="contact.name"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                           placeholder="Nama lengkap kontak person">
                                </div>
                                <div>
                                    <label :for="'contact_phone_' + index" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Telepon Kontak
                                    </label>
                                    <input type="text" :name="'contacts[' + index + '][phone]'" :id="'contact_phone_' + index" x-model="contact.phone"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                           placeholder="Contoh: 0717-123456">
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="contacts.push({name: '', phone: ''})" class="flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Kontak Lain
                    </button>

                    <div>
                        <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Kontak (Opsional)</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('contact_email') border-red-500 @enderror"
                               placeholder="email@example.com">
                        @error('contact_email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status --}}
            <x-admin.card title="Status & Publikasi" subtitle="Pengaturan status lelang">
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'upcoming') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Jadikan Unggulan</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_urgent" id="is_urgent" value="1"
                               {{ old('is_urgent') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="is_urgent" class="ml-2 text-sm text-gray-700">Mendesak</label>
                    </div>
                </div>
            </x-admin.card>

            {{-- Images --}}
            <x-admin.card title="Gambar Aset" subtitle="Upload minimal 3 gambar aset yang dilelang">
                <div>
                    <div class="relative">
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="hidden" onchange="previewImages(event)" required>
                        <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-xs text-gray-500 font-medium">Upload Gambar</p>
                                <p class="text-xs text-gray-400">PNG, JPG, WEBP (Max 5MB per file)</p>
                                <p class="text-xs text-red-500 font-medium mt-1">Minimal 3 gambar wajib</p>
                            </div>
                        </label>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-2 gap-2"></div>
                    <div id="image-count-warning" class="mt-2 text-sm text-red-600 hidden">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Minimal 3 gambar diperlukan untuk lelang
                    </div>
                    @error('images')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </x-admin.card>

            {{-- Actions --}}
            <div class="flex flex-col gap-3">
                <x-admin.button type="submit" class="w-full">
                    Simpan Lelang Agunan
                </x-admin.button>
                <x-admin.button href="{{ route('admin.auctions.index') }}" variant="secondary" class="w-full">
                    Batal
                </x-admin.button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let selectedImages = [];

function previewImages(event) {
    const files = event.target.files;
    const preview = document.getElementById('image-preview');
    const warning = document.getElementById('image-count-warning');

    selectedImages = Array.from(files);
    preview.innerHTML = '';

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg">
                    <button type="button" onclick="removeImage(${i})"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600">
                        ×
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    }

    // Show/hide warning based on image count
    if (files.length < 3) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }

    // Update form validation
    updateImageValidation();
}

function removeImage(index) {
    const dt = new DataTransfer();
    const input = document.getElementById('images');
    const files = input.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }

    input.files = dt.files;
    previewImages({ target: input });
}

function updateImageValidation() {
    const files = document.getElementById('images').files;
    const submitButton = document.querySelector('button[type="submit"]');

    if (files.length < 3) {
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        submitButton.title = 'Minimal 3 gambar diperlukan';
    } else {
        submitButton.disabled = false;
        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        submitButton.title = '';
    }
}

// Auto-calculate deposit amount based on limit price and percentage
document.getElementById('limit_price').addEventListener('input', calculateDeposit);
document.getElementById('deposit_percentage').addEventListener('input', calculateDeposit);

function calculateDeposit() {
    const limitPrice = parseFloat(document.getElementById('limit_price').value) || 0;
    const percentage = parseFloat(document.getElementById('deposit_percentage').value) || 20;
    const depositAmount = Math.round(limitPrice * percentage / 100);

    if (limitPrice > 0) {
        document.getElementById('deposit_amount').value = depositAmount;
    }
}

// Form validation on submit
document.getElementById('auction-form').addEventListener('submit', function(e) {
    const files = document.getElementById('images').files;
    if (files.length < 3) {
        e.preventDefault();
        alert('Minimal 3 gambar diperlukan untuk lelang agunan');
        return false;
    }
});

// Initialize validation on page load
document.addEventListener('DOMContentLoaded', function() {
    updateImageValidation();
});
</script>
@endpush
@endsection
