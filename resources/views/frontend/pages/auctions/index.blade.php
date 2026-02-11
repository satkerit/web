<x-frontend-layout>
    <x-slot name="title">Lelang Agunan - {{ config('app.name') }}</x-slot>

    @push('head')
    <!-- SEO Meta Tags -->
    <meta name="description" content="Temukan berbagai lelang agunan terpercaya dengan harga terbaik. Rumah, tanah, ruko, dan properti komersial lainnya.">
    <meta name="keywords" content="lelang agunan, lelang properti, BPRS Babel, auction, property auction, rumah lelang, tanah lelang">
    <meta name="author" content="BPRS Bangka Belitung">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Lelang Agunan - {{ config('app.name') }}">
    <meta property="og:description" content="Temukan berbagai lelang agunan terpercaya dengan harga terbaik. Rumah, tanah, ruko, dan properti komersial lainnya.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Auction-specific styles -->
    <style>
        /* Auction-specific styling */
        .auction-hero {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #115e59 100%);
        }

        .btn-auction-primary {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-auction-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }

        .auction-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auction-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .auction-price {
            background: linear-gradient(135deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .status-active {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
        }

        .status-upcoming {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
        }

        .status-sold {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
        }

        .auction-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .slide-up-auction {
            animation: slideUp 0.8s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .scale-in-auction {
            animation: scaleIn 0.6s ease-out forwards;
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .float-auction {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(-5px) rotate(-1deg); }
        }

        .glass-auction {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auction-search-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(16, 185, 129, 0.2);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
    @endpush

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 pt-32 pb-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <!-- Animated Gradient Orbs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-6 py-3 glass-auction rounded-full text-sm font-semibold mb-6 scale-in-auction">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-emerald-50">Lelang Agunan Terpercaya</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 slide-up-auction tracking-tight text-white">
                    Temukan Agunan <span class="text-emerald-300 auction-pulse">Impian Anda</span>
                </h1>
                <p class="text-xl md:text-2xl text-emerald-100 mb-8 max-w-3xl mx-auto slide-up-auction" style="animation-delay: 0.2s;">
                    Dapatkan agunan berkualitas dengan harga terbaik melalui lelang resmi dan terpercaya
                </p>

                <!-- Search Form -->
                <div class="max-w-5xl mx-auto slide-up-auction" style="animation-delay: 0.4s;">
                    <form method="GET" class="auction-search-form rounded-2xl p-6 md:p-8 shadow-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            <div class="space-y-2 text-left">
                                <label class="block text-sm font-bold tracking-tight text-gray-700">Cari Agunan</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Lokasi, jenis agunan..."
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 transition-all">
                            </div>
                            <div class="space-y-2 text-left">
                                <label class="block text-sm font-semibold text-gray-700">Jenis Aset</label>
                                <select name="asset_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 transition-all">
                                    <option value="">Semua Jenis</option>
                                    @foreach($assetTypes as $value => $label)
                                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2 text-left">
                                <label class="block text-sm font-semibold text-gray-700">Kota</label>
                                <select name="city" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 transition-all">
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
                                <button type="submit" class="w-full btn-auction-primary py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-bold tracking-tight">
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
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 md:p-8 mb-8 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                                <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </span>
                                Filter Lanjutan
                            </h3>
                            <button onclick="toggleFilters()" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">
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
                                           placeholder="0" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Harga Maksimum</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                           placeholder="Unlimited" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Status Lelang</label>
                                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
                                        <option value="">Semua Status</option>
                                        <option value="registration_open" {{ request('status') === 'registration_open' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                        <option value="auction_scheduled" {{ request('status') === 'auction_scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Urutkan Berdasarkan</label>
                                    <select name="sort_by" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
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
                            <span class="text-sm text-emerald-600 ml-2">
                                    (dengan filter aktif)
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Urutan:</span>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'asc'])) }}"
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'asc' ? 'bg-emerald-100 border-emerald-300 text-emerald-700' : 'border-gray-300 hover:border-emerald-300' }}">
                                Ascending
                            </a>
                            <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'desc'])) }}"
                               class="px-3 py-2 text-sm border rounded-lg transition-all {{ request('sort_order') === 'desc' ? 'bg-emerald-100 border-emerald-300 text-emerald-700' : 'border-gray-300 hover:border-emerald-300' }}">
                                Descending
                            </a>
                        </div>
                    </div>

                    <!-- Auction Grid -->
                    <div class="auction-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                        @forelse($auctions as $auction)
                            <div class="auction-card rounded-2xl overflow-hidden shadow-xl shadow-gray-200/50 border border-gray-100 group" x-intersect>
                                <!-- Image -->
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    @if($auction->main_image)
                                        <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-100 via-emerald-200 to-teal-200 flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="h-16 w-16 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <p class="text-emerald-600 font-medium text-sm">{{ $auction->asset_type_label }}</p>
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

                                    <!-- Sold Watermark -->
                                    @if($auction->status === 'sold')
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 z-20 pointer-events-none">
                                        <div class="transform -rotate-12 bg-red-600/90 text-white px-10 py-3 text-2xl md:text-3xl font-black tracking-widest border-4 border-white shadow-2xl uppercase backdrop-blur-sm">
                                            TERJUAL
                                        </div>
                                    </div>
                                    @endif

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
                                            <a href="{{ route('auctions.show', $auction) }}"
                                               class="w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
                                               title="Lihat Detail">
                                                <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            {{ $auction->asset_type_label }}
                                        </span>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
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
                                    <a href="{{ route('auctions.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
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
                                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors line-clamp-2">
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
                                <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors line-clamp-2">
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
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @if($upcoming->auction_date)
                                                        {{ $upcoming->auction_date->format('d M Y') }}
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
                </div>
            </div>
        </div>
    </section>


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
    </script>
    @endpush
</x-frontend-layout>
