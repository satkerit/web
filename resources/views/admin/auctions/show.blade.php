<x-admin-auction-layout>
    <x-slot name="header">Detail Lelang Agunan</x-slot>
    <x-slot name="subtitle">{{ $auction->title }}</x-slot>

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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Lelang</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('admin.auctions.edit', $auction) }}"
               class="btn-auction-admin-primary inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Lelang
            </a>
            <a href="{{ route('auctions.show', $auction) }}" target="_blank"
               class="btn-auction-admin-success inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Lihat di Frontend
            </a>
            <a href="{{ route('admin.auctions.index') }}"
               class="btn-auction-admin-secondary inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header Card -->
                <div class="admin-auction-card p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $auction->title }}</h1>
                            <p class="text-lg text-gray-600 mb-4">{{ $auction->auction_number }}</p>
                            @if($auction->description)
                                <p class="text-gray-700 leading-relaxed">{{ $auction->description }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col space-y-2 ml-4">
                            @if($auction->is_featured)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Featured
                                </span>
                            @endif
                            @if($auction->is_urgent)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Urgent
                                </span>
                            @endif
                            <span class="status-badge status-{{ $auction->status }}">
                                {{ $auction->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Images Gallery -->
                @if($auction->images && count($auction->images) > 0)
                    <div class="admin-auction-card p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Galeri Foto Objek Lelang
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($auction->images as $index => $image)
                                <div class="relative group cursor-pointer" onclick="openImageModal({{ $index }})">
                                    <img src="{{ \App\Helpers\StorageHelper::url($image) }}"
                                         alt="Foto {{ $auction->title }} - {{ $index + 1 }}"
                                         class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                        {{ $index + 1 }}/{{ count($auction->images) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="admin-auction-card p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Informasi Cepat
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Jenis Aset:</span>
                            <span class="font-medium text-gray-900">{{ $auction->asset_type_label }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Harga Limit:</span>
                            <span class="font-bold text-green-600 text-lg">{{ $auction->formatted_limit_price }}</span>
                        </div>
                        @if($auction->estimated_price)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Nilai Taksiran:</span>
                                <span class="font-medium text-gray-900">{{ $auction->formatted_estimated_price }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Tanggal Lelang:</span>
                            <div class="text-right">
                                @if($auction->auction_date)
                                    <div class="font-medium text-gray-900">{{ $auction->auction_date->format('d F Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $auction->auction_date->format('H:i') }} WIB</div>
                                @else
                                    <span class="text-gray-500">Belum ditentukan</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Lokasi Lelang:</span>
                            <span class="font-medium text-gray-900 text-right">{{ $auction->auction_location }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="admin-auction-card p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Statistik
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($auction->view_count) }}</div>
                            <div class="text-xs text-blue-600">Views</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($auction->interest_count) }}</div>
                            <div class="text-xs text-green-600">Interest</div>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($auction->download_count) }}</div>
                            <div class="text-xs text-purple-600">Downloads</div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="admin-auction-card p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Kontak
                    </h3>
                    <div class="space-y-3">
                        @if($auction->contacts && is_array($auction->contacts) && count($auction->contacts) > 0)
                            @foreach($auction->contacts as $index => $contact)
                                <div class="{{ $index > 0 ? 'pt-3 border-t border-gray-100' : '' }}">
                                    <div class="font-medium text-gray-900">{{ $contact['name'] ?? '-' }}</div>
                                    <div class="text-sm text-gray-600 mb-1">{{ $contact['position'] ?? 'Staf Lelang' }}</div>
                                    <div class="space-y-1">
                                        @if(isset($contact['phone']) && $contact['phone'])
                                            <a href="tel:{{ $contact['phone'] }}" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $contact['phone'] }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <span class="text-sm text-gray-600">Kontak Person:</span>
                                <div class="font-medium text-gray-900">{{ $auction->contact_person }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Telepon:</span>
                                <div class="font-medium text-gray-900">
                                    <a href="tel:{{ $auction->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $auction->contact_phone }}
                                    </a>
                                </div>
                            </div>
                            @if($auction->contact_email)
                                <div>
                                    <span class="text-sm text-gray-600">Email:</span>
                                    <div class="font-medium text-gray-900">
                                        <a href="mailto:{{ $auction->contact_email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $auction->contact_email }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information Tabs -->
        <div class="admin-auction-card">
            <div class="border-b border-gray-200 px-6 pt-6">
                <nav class="-mb-px flex space-x-8">
                    <button class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-tab="property">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Properti
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-tab="auction">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                        </svg>
                        Lelang Agunan
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-tab="legal">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Legal
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                            data-tab="contact">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Kontak
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <div class="tab-content" id="property">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Detail Properti
                            </h4>
                            <div class="space-y-3">
                                @if($auction->asset_category)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kategori:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->asset_category }}</span>
                                    </div>
                                @endif
                                @if($auction->land_area)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Luas Tanah:</span>
                                        <span class="font-medium text-gray-900">{{ number_format($auction->land_area, 0) }} m²</span>
                                    </div>
                                @endif
                                @if($auction->building_area)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Luas Bangunan:</span>
                                        <span class="font-medium text-gray-900">{{ number_format($auction->building_area, 0) }} m²</span>
                                    </div>
                                @endif
                                @if($auction->floors)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Jumlah Lantai:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->floors }}</span>
                                    </div>
                                @endif
                                @if($auction->bedrooms)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kamar Tidur:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->bedrooms }}</span>
                                    </div>
                                @endif
                                @if($auction->bathrooms)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kamar Mandi:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->bathrooms }}</span>
                                    </div>
                                @endif
                                @if($auction->parking_spaces)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Tempat Parkir:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->parking_spaces }}</span>
                                    </div>
                                @endif
                                @if($auction->year_built)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Tahun Dibangun:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->year_built }}</span>
                                    </div>
                                @endif
                                @if($auction->building_condition)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kondisi Bangunan:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->building_condition }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Lokasi
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-600 block mb-1">Alamat:</span>
                                    <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->full_address }}</div>
                                </div>
                                @if($auction->facilities)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Fasilitas:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->facilities }}</div>
                                    </div>
                                @endif
                                @if($auction->nearby_facilities)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Fasilitas Sekitar:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->nearby_facilities }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content hidden" id="auction">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z"></path>
                                </svg>
                                Informasi Lelang Agunan
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Jenis Lelang Agunan:</span>
                                    <span class="font-medium text-gray-900">{{ $auction->auction_type_label }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Metode:</span>
                                    <span class="font-medium text-gray-900">{{ $auction->auction_method ?? 'Lelang Agunan Terbuka' }}</span>
                                </div>
                                @if($auction->registration_start && $auction->registration_end)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Pendaftaran:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->registration_start->format('d/m/Y') }} - {{ $auction->registration_end->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                                @if($auction->viewing_start && $auction->viewing_end)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Viewing:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->viewing_start->format('d/m/Y') }} - {{ $auction->viewing_end->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Harga & Pembayaran
                            </h4>
                            <div class="space-y-3">
                                @if($auction->calculated_deposit)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Uang Jaminan:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->formatted_calculated_deposit }}</span>
                                    </div>
                                @endif
                                @if($auction->increment_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kelipatan Penawaran:</span>
                                        <span class="font-medium text-gray-900">Rp {{ number_format($auction->increment_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Batas Pelunasan:</span>
                                    <span class="font-medium text-gray-900">{{ $auction->payment_deadline_days }} hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content hidden" id="legal">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Sertifikat
                            </h4>
                            <div class="space-y-3">
                                @if($auction->certificate_type)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Jenis Sertifikat:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->certificate_type_label }}</span>
                                    </div>
                                @endif
                                @if($auction->certificate_number)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Nomor Sertifikat:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->certificate_number }}</span>
                                    </div>
                                @endif
                                @if($auction->certificate_date)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Tanggal Terbit:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->certificate_date->format('d F Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Legal
                            </h4>
                            <div class="space-y-3">
                                @if($auction->creditor_name)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Kreditur:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->creditor_name }}</span>
                                    </div>
                                @endif
                                @if($auction->debt_amount)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Jumlah Hutang:</span>
                                        <span class="font-medium text-gray-900">Rp {{ number_format($auction->debt_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($auction->court_decision)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600">Putusan Pengadilan:</span>
                                        <span class="font-medium text-gray-900">{{ $auction->court_decision }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content hidden" id="contact">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Penyelenggara
                            </h4>
                            <div class="space-y-3">
                                @if($auction->organizer_name)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Nama:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->organizer_name }}</div>
                                    </div>
                                @endif
                                @if($auction->organizer_address)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Alamat:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->organizer_address }}</div>
                                    </div>
                                @endif
                                @if($auction->organizer_phone)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Telepon:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">
                                            <a href="tel:{{ $auction->organizer_phone }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $auction->organizer_phone }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-4 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Kontak Person
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-gray-600 block mb-1">Nama:</span>
                                    <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->contact_person }}</div>
                                </div>
                                @if($auction->contact_position)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Jabatan:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $auction->contact_position }}</div>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-gray-600 block mb-1">Telepon:</span>
                                    <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">
                                        <a href="tel:{{ $auction->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $auction->contact_phone }}
                                        </a>
                                    </div>
                                </div>
                                @if($auction->contact_email)
                                    <div>
                                        <span class="text-gray-600 block mb-1">Email:</span>
                                        <div class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">
                                            <a href="mailto:{{ $auction->contact_email }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $auction->contact_email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                    <button onclick="previousImage()" class="bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="nextImage()" class="bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image gallery data
        const images = @json($auction->images ? array_map(function($image) { return \App\Helpers\StorageHelper::url($image); }, $auction->images) : []);
        let currentImageIndex = 0;

        // Tab functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.dataset.tab;

                // Remove active class from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-orange-500', 'text-orange-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });

                // Add active class to clicked button
                this.classList.add('active', 'border-orange-500', 'text-orange-600');
                this.classList.remove('border-transparent', 'text-gray-500');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected tab content
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Set first tab as active
        document.querySelector('.tab-button[data-tab="property"]').classList.add('border-orange-500', 'text-orange-600');
        document.querySelector('.tab-button[data-tab="property"]').classList.remove('border-transparent', 'text-gray-500');

        // Image modal functions
        function openImageModal(index) {
            if (images.length === 0) return;

            currentImageIndex = index;
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = images[currentImageIndex];
            modalImage.alt = `{{ $auction->title }} - Foto ${currentImageIndex + 1}`;
            modal.classList.remove('hidden');

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');

            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        function nextImage() {
            if (images.length === 0) return;

            currentImageIndex = (currentImageIndex + 1) % images.length;
            const modalImage = document.getElementById('modalImage');
            modalImage.src = images[currentImageIndex];
            modalImage.alt = `{{ $auction->title }} - Foto ${currentImageIndex + 1}`;
        }

        function previousImage() {
            if (images.length === 0) return;

            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            const modalImage = document.getElementById('modalImage');
            modalImage.src = images[currentImageIndex];
            modalImage.alt = `{{ $auction->title }} - Foto ${currentImageIndex + 1}`;
        }

        // Keyboard navigation for image modal
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (!modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        closeImageModal();
                        break;
                    case 'ArrowLeft':
                        previousImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                }
            }
        });

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
    @endpush
</x-admin-auction-layout>
