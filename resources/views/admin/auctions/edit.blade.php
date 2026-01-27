<x-admin-auction-layout>
    <x-slot name="header">Edit Lelang Agunan</x-slot>
    <x-slot name="subtitle">Perbarui informasi lelang agunan dengan detail yang akurat</x-slot>

    <div class="animate-fade-in-up">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.auctions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Daftar Lelang
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit Lelang</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="admin-auction-card p-8">
                    <form method="POST" action="{{ route('admin.auctions.update', $auction) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form Header -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Edit Lelang Agunan</h3>
                            <p class="text-gray-600">Perbarui informasi lelang agunan: <span class="font-semibold text-orange-600">{{ $auction->title }}</span></p>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-xl border border-orange-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Dasar
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Lelang Agunan *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $auction->title) }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="auction_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Lelang Agunan *</label>
                                    <input type="text" name="auction_number" id="auction_number" value="{{ old('auction_number', $auction->auction_number) }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('auction_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="asset_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Aset *</label>
                                    <select name="asset_type" id="asset_type" class="admin-auction-input w-full" required>
                                        <option value="">Pilih Jenis Aset</option>
                                        @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('asset_type', $auction->asset_type) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asset_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $auction->city) }}" 
                                           class="admin-auction-input w-full">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="limit_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Limit *</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input type="number" name="limit_price" id="limit_price" value="{{ old('limit_price', $auction->limit_price) }}" 
                                               class="admin-auction-input w-full pl-12" required>
                                    </div>
                                    @error('limit_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                    <select name="status" id="status" class="admin-auction-input w-full" required>
                                        @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $auction->status) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Auction Details Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                </svg>
                                Detail Lelang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="auction_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lelang Agunan *</label>
                                    <input type="datetime-local" name="auction_date" id="auction_date" 
                                           value="{{ old('auction_date', $auction->auction_date ? $auction->auction_date->format('Y-m-d\TH:i') : '') }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('auction_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="auction_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Lelang Agunan *</label>
                                    <select name="auction_type" id="auction_type" class="admin-auction-input w-full" required>
                                        <option value="">Pilih Jenis Lelang Agunan</option>
                                        @foreach(\App\Models\Auction::$auctionTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('auction_type', $auction->auction_type) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('auction_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="auction_location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Lelang Agunan *</label>
                                    <input type="text" name="auction_location" id="auction_location" value="{{ old('auction_location', $auction->auction_location) }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('auction_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Informasi Kontak
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Kontak Person *</label>
                                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $auction->contact_person) }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('contact_person')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon Kontak *</label>
                                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $auction->contact_phone) }}" 
                                           class="admin-auction-input w-full" required>
                                    @error('contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Alamat Objek
                            </h4>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="admin-auction-input w-full" required>{{ old('address', $auction->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl border border-yellow-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Deskripsi
                            </h4>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail</label>
                                <textarea name="description" id="description" rows="4" 
                                          class="admin-auction-input w-full" placeholder="Masukkan deskripsi detail tentang objek lelang agunan...">{{ old('description', $auction->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Gambar Objek
                            </h4>
                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*" 
                                       class="admin-auction-input w-full">
                                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WebP. Maksimal 5MB per file.</p>
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                @if($auction->images && count($auction->images) > 0)
                                    <div class="mt-6">
                                        <p class="text-sm font-medium text-gray-700 mb-3">Gambar Saat Ini:</p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @foreach($auction->images as $image)
                                                <div class="relative group">
                                                    <img src="{{ \App\Helpers\StorageHelper::url($image) }}" 
                                                         alt="Auction Image" 
                                                         class="w-full h-24 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-sm text-amber-600">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Upload gambar baru akan mengganti semua gambar yang ada
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.auctions.index') }}" 
                               class="btn-auction-admin-secondary inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Batal
                            </a>
                            <div class="flex space-x-3">
                                <button type="submit" name="status" value="draft"
                                        class="btn-auction-admin-secondary inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Simpan Draft
                                </button>
                                <button type="submit" 
                                        class="btn-auction-admin-primary inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Lelang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-auction-layout>