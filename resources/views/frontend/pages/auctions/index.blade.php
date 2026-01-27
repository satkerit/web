<x-auction-layout>
    <x-slot name="title">Lelang Agunan - {{ config('app.name') }}</x-slot>
    <x-slot name="metaDescription">Temukan berbagai lelang agunan terpercaya dengan harga terbaik. Rumah, tanah, ruko, dan properti komersial lainnya.</x-slot>
    <x-slot name="metaKeywords">lelang agunan, lelang properti, BPRS Babel, auction, property auction, rumah lelang, tanah lelang</x-slot>

    <!-- Hero Section -->
    <section class="relative auction-hero bg-gradient-to-br from-orange-600 via-red-600 to-pink-600 text-white py-20 md:py-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl float-auction"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-yellow-400/20 rounded-full blur-xl float-auction" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-6 py-3 glass-auction rounded-full text-sm font-semibold mb-6 scale-in-auction">
                    <svg class="w-5 h-5 mr-3 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-yellow-100">Lelang Agunan Terpercaya</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 slide-up-auction">
                    Temukan Agunan <span class="text-yellow-300 auction-pulse">Impian Anda</span>
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto slide-up-auction" style="animation-delay: 0.2s;">
                    Dapatkan agunan berkualitas dengan harga terbaik melalui lelang resmi dan terpercaya
                </p>
                
                <!-- Search Form -->
                <div class="max-w-5xl mx-auto slide-up-auction" style="animation-delay: 0.4s;">
                    <form method="GET" class="auction-search-form rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Cari Agunan</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Lokasi, jenis agunan..."
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-900 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Jenis Aset</label>
                                <select name="asset_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-900 transition-all">
                                    <option value="">Semua Jenis</option>
                                    @foreach($assetTypes as $value => $label)
                                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Kota</label>
                                <select name="city" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent text-gray-900 transition-all">
                                    <option value="">Semua Kota</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">&nbsp;</label>
                                <button type="submit" class="w-full btn-auction-primary py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-bold">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Cari Lelang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <!-- Advanced Filters -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-8 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </span>
                                Filter Lanjutan
                            </h3>
                            <button onclick="toggleFilters()" class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                                <span id="filter-toggle-text">Tampilkan</span>
                                <svg id="filter-toggle-icon" class="w-4 h-4 inline ml-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div id="advanced-filters" class="hidden">
                            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="asset_type" value="{{ request('asset_type') }}">
                                <input type="hidden" name="city" value="{{ request('city') }}">
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Harga Minimum</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                           placeholder="0" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Harga Maksimum</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                           placeholder="Unlimited" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Status Lelang</label>
                                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 transition-all">
                                        <option value="">Semua Status</option>
                                        <option value="registration_open" {{ request('status') === 'registration_open' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                        <option value="auction_scheduled" {{ request('status') === 'auction_scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Urutkan Berdasarkan</label>
                                    <select name="sort_by" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 transition-all">
                                        <option value="date" {{ request('sort_by') === 'date' ? 'selected' : '' }}>Tanggal Lelang</option>
                                        <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Harga</option>
                                        <option value="featured" {{ request('sort_by') === 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 lg:col-span-4 flex gap-4">
                                    <button type="submit" class="flex-1 btn-auction-primary py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 font-bold">
                                        Terapkan Filter
                                    </button>
                                    <a href="{{ route('auctions.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all duration-300 text-center">
                                        Reset Filter
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Results Info & Sort -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                        <div class="text-gray-600">
                            <span class="font-semibold text-gray-900">{{ $auctions->total() }}</span> lelang ditemukan
                            @if(request()->hasAny(['search', 'asset_type', 'city', 'min_price', 'max_price', 'status']))
                            <span class="text-sm text-orange-600 ml-2">
                                    (dengan filter aktif)
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Urutan:</span>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'asc'])) }}" 
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'asc' ? 'bg-orange-100 border-orange-300 text-orange-700' : 'border-gray-300 hover:border-orange-300' }}">
                                Ascending
                            </a>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'desc'])) }}" 
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'desc' ? 'bg-orange-100 border-orange-300 text-orange-700' : 'border-gray-300 hover:border-orange-300' }}">
                                Descending
                            </a>
                        </div>
                    </div>

                    <!-- Auction Grid -->
                    <div class="auction-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                        @forelse($auctions as $auction)
                            <div class="auction-card rounded-2xl overflow-hidden shadow-xl border border-gray-100 group" x-intersect>
                                <!-- Image -->
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    @if($auction->main_image)
                                        <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-orange-100 via-orange-200 to-red-200 flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="h-16 w-16 text-orange-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <p class="text-orange-600 font-medium text-sm">{{ $auction->asset_type_label }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col space-y-2">
                                        @if($auction->is_featured)
                                            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg auction-pulse">
                                                ⭐ Featured
                                            </span>
                                        @endif
                                        @if($auction->is_urgent)
                                            <span class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg auction-pulse">
                                                🔥 Urgent
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold 
                                            @if($auction->status === 'registration_open') status-active
                                            @elseif($auction->status === 'auction_scheduled') status-upcoming
                                            @elseif($auction->status === 'sold') status-sold
                                            @else status-closed
                                            @endif shadow-lg backdrop-blur-sm">
                                            <span class="w-2 h-2 bg-current rounded-full mr-2 opacity-75"></span>
                                            {{ $auction->status_label }}
                                        </span>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex space-x-2">
                                            <button onclick="showInterestModal({{ $auction->id }})" 
                                                    class="w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
                                                    title="Nyatakan Minat">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </button>
                                            <a href="{{ route('auctions.show', $auction) }}" 
                                               class="w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
                                               title="Lihat Detail">
                                                <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                            {{ $auction->asset_type_label }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-orange-600 transition-colors">
                                        <a href="{{ route('auctions.show', $auction) }}">
                                            {{ $auction->title }}
                                        </a>
                                    </h3>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-4">
                                        <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $auction->city ?? 'Lokasi tidak tersedia' }}
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4">
                                        <div class="text-2xl font-bold auction-price">{{ $auction->formatted_limit_price }}</div>
                                        @if($auction->estimated_price)
                                            <div class="text-sm text-gray-500">Estimasi: {{ $auction->formatted_estimated_price }}</div>
                                        @endif
                                    </div>

                                    <!-- Auction Info -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Tanggal Lelang
                                            </span>
                                            <span class="font-semibold text-gray-900">
                                                @if($auction->auction_date)
                                                    {{ $auction->auction_date->format('d M Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 flex items-center">
                                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Waktu
                                            </span>
                                            <span class="font-semibold text-gray-900">
                                                @if($auction->auction_date)
                                                    {{ $auction->auction_date->format('H:i') }} WIB
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    @if($auction->days_until_auction >= 0 && $auction->auction_date)
                                        <div class="countdown-timer text-center mb-4" data-end-time="{{ $auction->auction_date->toISOString() }}">
                                            {{ $auction->time_until_auction }}
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <a href="{{ route('auctions.show', $auction) }}" 
                                       class="block w-full btn-auction-primary text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                        Lihat Detail Lelang
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-16 bg-white rounded-2xl shadow-xl">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada lelang ditemukan</h3>
                                    <p class="text-gray-600 mb-6">Belum ada lelang yang sesuai dengan kriteria pencarian Anda.</p>
                                    <a href="{{ route('auctions.index') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Reset Pencarian
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($auctions->hasPages())
                        <div class="mt-12">
                            <div class="bg-white rounded-2xl shadow-xl p-6">
                                {{ $auctions->links() }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:w-1/4 space-y-8">
                    <!-- Featured Auctions -->
                    @if($featuredAuctions->count() > 0)
                        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </span>
                                Lelang Unggulan
                            </h3>
                            <div class="space-y-4">
                                @foreach($featuredAuctions as $featured)
                                    <div class="group border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex gap-3">
                                            @if($featured->main_image)
                                                <img src="{{ $featured->main_image }}" alt="{{ $featured->title }}" 
                                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                    <a href="{{ route('auctions.show', $featured) }}">
                                                        {{ $featured->title }}
                                                    </a>
                                                </h4>
                                                <div class="text-xs text-gray-600 mb-2 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $featured->city }}
                                                </div>
                                                <div class="text-sm font-bold text-emerald-600">{{ $featured->formatted_limit_price }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @if($featured->auction_date)
                                                        {{ $featured->auction_date->format('d M Y') }}
                                                    @else
                                                        Belum ditentukan
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upcoming Auctions -->
                    @if($upcomingAuctions->count() > 0)
                        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                Lelang Mendatang
                            </h3>
                            <div class="space-y-4">
                                @foreach($upcomingAuctions as $upcoming)
                                    <div class="group border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex gap-3">
                                            @if($upcoming->main_image)
                                                <img src="{{ $upcoming->main_image }}" alt="{{ $upcoming->title }}" 
                                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                    <a href="{{ route('auctions.show', $upcoming) }}">
                                                        {{ $upcoming->title }}
                                                    </a>
                                                </h4>
                                                <div class="text-xs text-gray-600 mb-2 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $upcoming->city }}
                                                </div>
                                                <div class="text-sm font-bold text-emerald-600">{{ $upcoming->formatted_limit_price }}</div>
                                                <div class="text-xs text-orange-600 font-semibold mt-1">{{ $upcoming->time_until_auction }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-primary-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            Statistik Lelang
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-white/80">Total Lelang</span>
                                <span class="font-bold text-xl">{{ $auctions->total() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/80">Aktif</span>
                                <span class="font-bold text-xl">{{ $auctions->where('status', '!=', 'sold')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/80">Terjual</span>
                                <span class="font-bold text-xl">{{ $auctions->where('status', 'sold')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interest Modal -->
    <div id="interest-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </span>
                        Nyatakan Minat
                    </h3>
                    <button onclick="hideInterestModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form id="interest-form" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan (Opsional)</label>
                        <textarea name="message" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" 
                                class="flex-1 btn-auction-primary py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 font-bold">
                            Kirim Minat
                        </button>
                        <button type="button" onclick="hideInterestModal()" 
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all duration-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFilters() {
            const filters = document.getElementById('advanced-filters');
            const toggleText = document.getElementById('filter-toggle-text');
            const toggleIcon = document.getElementById('filter-toggle-icon');
            
            if (filters.classList.contains('hidden')) {
                filters.classList.remove('hidden');
                toggleText.textContent = 'Sembunyikan';
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                filters.classList.add('hidden');
                toggleText.textContent = 'Tampilkan';
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        }

        function showInterestModal(auctionId) {
            document.getElementById('interest-form').action = `/auctions/${auctionId}/interest`;
            document.getElementById('interest-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideInterestModal() {
            document.getElementById('interest-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('interest-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideInterestModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideInterestModal();
            }
        });
    </script>
    @endpush
</x-auction-layout>