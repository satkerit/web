<x-frontend-layout>
    <x-slot name="title">Lelang Properti - {{ config('app.name') }}</x-slot>
    <x-slot name="meta_description">Temukan berbagai lelang properti terpercaya dengan harga terbaik. Rumah, tanah, ruko, dan properti komersial lainnya.</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-emerald-600 text-white py-20 md:py-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-emerald-400/20 rounded-full blur-xl animate-float-delayed"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold mb-6 animate-bounce-in">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Lelang Properti Terpercaya
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in-up">
                    Temukan Properti <span class="text-emerald-300">Impian Anda</span>
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto animate-fade-in-up delay-200">
                    Dapatkan properti berkualitas dengan harga terbaik melalui lelang resmi dan terpercaya
                </p>
                
                <!-- Search Form -->
                <div class="max-w-5xl mx-auto animate-fade-in-up delay-300">
                    <form method="GET" class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Cari Properti</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Lokasi, jenis properti..."
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Jenis Aset</label>
                                <select name="asset_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 transition-all">
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
                                <select name="city" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 transition-all">
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
                                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
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
                                <span class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </span>
                                Filter Lanjutan
                            </h3>
                            <button onclick="toggleFilters()" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
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
                                           placeholder="0" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Harga Maksimum</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                           placeholder="Unlimited" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Status Lelang</label>
                                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                        <option value="">Semua Status</option>
                                        <option value="registration_open" {{ request('status') === 'registration_open' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                        <option value="auction_scheduled" {{ request('status') === 'auction_scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Urutkan Berdasarkan</label>
                                    <select name="sort_by" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                        <option value="date" {{ request('sort_by') === 'date' ? 'selected' : '' }}>Tanggal Lelang</option>
                                        <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Harga</option>
                                        <option value="featured" {{ request('sort_by') === 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 lg:col-span-4 flex gap-4">
                                    <button type="submit" class="flex-1 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
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
                                <span class="text-sm text-primary-600 ml-2">
                                    (dengan filter aktif)
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Urutan:</span>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'asc'])) }}" 
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'asc' ? 'bg-primary-100 border-primary-300 text-primary-700' : 'border-gray-300 hover:border-primary-300' }}">
                                Ascending
                            </a>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'desc'])) }}" 
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'desc' ? 'bg-primary-100 border-primary-300 text-primary-700' : 'border-gray-300 hover:border-primary-300' }}">
                                Descending
                            </a>
                        </div>
                    </div>

                <!-- Auction Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($auctions as $auction)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <!-- Image -->
                            <div class="relative">
                                @if($auction->main_image)
                                    <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                    @if($auction->is_featured)
                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            Featured
                                        </span>
                                    @endif
                                    @if($auction->is_urgent)
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            Urgent
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Status -->
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $auction->status_color['bg'] }} {{ $auction->status_color['text'] }}">
                                        {{ $auction->status_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="mb-2">
                                    <span class="text-sm text-blue-600 font-medium">{{ $auction->asset_type_label }}</span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('auctions.show', $auction) }}" class="hover:text-blue-600 transition duration-300">
                                        {{ $auction->title }}
                                    </a>
                                </h3>
                                
                                <div class="text-sm text-gray-600 mb-3 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $auction->city }}
                                </div>

                                <div class="mb-4">
                                    <div class="text-2xl font-bold text-green-600">{{ $auction->formatted_limit_price }}</div>
                                    @if($auction->estimated_price)
                                        <div class="text-sm text-gray-500">Taksiran: {{ $auction->formatted_estimated_price }}</div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                @if($auction->days_until_auction >= 0)
                                    <div class="text-sm text-orange-600 font-medium mb-4">
                                        {{ $auction->time_until_auction }}
                                    </div>
                                @endif

                                <div class="flex space-x-2">
                                    <a href="{{ route('auctions.show', $auction) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-300">
                                        Lihat Detail
                                    </a>
                                    <button onclick="showInterestModal({{ $auction->id }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition duration-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lelang</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada lelang yang sesuai dengan kriteria pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($auctions->hasPages())
                    <div class="mt-8">
                        {{ $auctions->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <!-- Featured Auctions -->
                @if($featuredAuctions->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lelang Unggulan</h3>
                        <div class="space-y-4">
                            @foreach($featuredAuctions as $featured)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <h4 class="font-medium text-gray-900 mb-1">
                                        <a href="{{ route('auctions.show', $featured) }}" class="hover:text-blue-600 transition duration-300">
                                            {{ Str::limit($featured->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-1">{{ $featured->city }}</div>
                                    <div class="text-sm font-semibold text-green-600">{{ $featured->formatted_limit_price }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($featured->auction_date)
                                            {{ $featured->auction_date->format('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upcoming Auctions -->
                @if($upcomingAuctions->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lelang Mendatang</h3>
                        <div class="space-y-4">
                            @foreach($upcomingAuctions as $upcoming)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <h4 class="font-medium text-gray-900 mb-1">
                                        <a href="{{ route('auctions.show', $upcoming) }}" class="hover:text-blue-600 transition duration-300">
                                            {{ Str::limit($upcoming->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-1">{{ $upcoming->city }}</div>
                                    <div class="text-sm font-semibold text-green-600">{{ $upcoming->formatted_limit_price }}</div>
                                    <div class="text-xs text-orange-600">{{ $upcoming->time_until_auction }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Interest Modal -->
    <div id="interest-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Nyatakan Minat</h3>
                <form id="interest-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan (Opsional)</label>
                        <textarea name="message" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                            Kirim
                        </button>
                        <button type="button" onclick="hideInterestModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition duration-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showInterestModal(auctionId) {
            document.getElementById('interest-form').action = `/auctions/${auctionId}/interest`;
            document.getElementById('interest-modal').classList.remove('hidden');
        }

        function hideInterestModal() {
            document.getElementById('interest-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('interest-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideInterestModal();
            }
        });
    </script>
    @endpush
</x-frontend-layout>