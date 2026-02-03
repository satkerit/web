<x-frontend-layout>
    <x-slot name="title">{{ $auction->title }} - Lelang Agunan</x-slot>

    @push('head')
        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $auction->meta_description ?? Str::limit($auction->description, 160) }}">
        <meta name="keywords"
            content="{{ $auction->meta_keywords ?? 'lelang agunan, ' . $auction->title . ', BPRS Babel' }}">
        <meta name="author" content="BPRS Bangka Belitung">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $auction->title }} - Lelang Agunan">
        <meta property="og:description"
            content="{{ $auction->meta_description ?? Str::limit($auction->description, 160) }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        @if($auction->main_image)
            <meta property="og:image" content="{{ $auction->main_image }}">
        @endif

        <!-- Auction-specific styles -->
        <style>
            /* Auction-specific styling */
            .auction-hero {
                background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #dc2626 100%);
            }

            .btn-auction-primary {
                background: linear-gradient(135deg, #f97316, #ea580c);
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-auction-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(249, 115, 22, 0.4);
            }

            .auction-card {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .auction-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            }

            .auction-price {
                background: linear-gradient(135deg, #f97316, #ea580c);
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
                background: linear-gradient(135deg, #f97316, #f59e0b);
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

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.05);
                    opacity: 0.9;
                }
            }
        </style>
    @endpush

    <!-- Breadcrumb -->
    <section class="bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-600 transition-colors">Beranda</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('auctions.index') }}"
                    class="text-gray-600 hover:text-orange-600 transition-colors">Lelang Agunan</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">{{ Str::limit($auction->title, 50) }}</span>
            </nav>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-8 border border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-700">
                                {{ $auction->asset_type_label }}
                            </span>
                            @if($auction->is_featured)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg">
                                    ⭐ Featured
                                </span>
                            @endif
                            @if($auction->is_urgent)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg animate-pulse">
                                    🔥 Urgent
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $auction->status_color['bg'] }} {{ $auction->status_color['text'] }} shadow-lg">
                                <span class="w-2 h-2 {{ $auction->status_color['dot'] }} rounded-full mr-2"></span>
                                {{ $auction->status_label }}
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-4">{{ $auction->title }}</h1>

                        <div class="flex flex-wrap items-center gap-6 text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium">{{ $auction->full_address ?: $auction->city }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ $auction->view_count ?? 0 }} kali dilihat</span>
                            </div>
                            @if($auction->auction_number)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    <span>No. Lelang: {{ $auction->auction_number }}</span>
                                </div>
                            @endif
                            @if($auction->object_number)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>No. Objek: {{ $auction->object_number }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex flex-wrap gap-3">
                        <button onclick="showInterestModal({{ $auction->id }})"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Nyatakan Minat
                        </button>
                        <button onclick="shareAuction()"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                            </svg>
                            Bagikan
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Sold Banner -->
                    @if($auction->status === 'sold')
                        <div
                            class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 md:p-8 text-white shadow-xl">
                            <div class="flex items-center gap-4 mb-4">
                                <div
                                    class="w-14 h-14 md:w-16 md:h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl md:text-2xl font-bold">Objek Telah Terjual</h2>
                                    @if($auction->sold_at)
                                        <p class="text-white/80 text-sm md:text-base">Terjual pada
                                            {{ $auction->sold_at->translatedFormat('d F Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($auction->winning_bid)
                                <div class="bg-white/10 rounded-xl p-4 md:p-6 backdrop-blur-sm">
                                    <p class="text-white/80 text-xs md:text-sm mb-1">Harga Terjual</p>
                                    <p class="text-3xl md:text-4xl font-bold">Rp
                                        {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                                    @if($auction->winner_name)
                                        <p class="text-white/80 mt-2 text-sm md:text-base">Pemenang: <span
                                                class="font-semibold text-white">{{ $auction->winner_name }}</span></p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                    <!-- Image Gallery -->
                    @if($auction->images && count($auction->images) > 0)
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden" x-data="{
                            activeImage: 0,
                            images: {{ json_encode(array_map(fn($img) => \App\Helpers\StorageHelper::url($img), $auction->images)) }},
                            next() { this.activeImage = (this.activeImage + 1) % this.images.length; },
                            prev() { this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length; }
                        }">
                            <div class="relative aspect-[16/10] md:aspect-[16/9] bg-gray-100">
                                <template x-for="(image, index) in images" :key="index">
                                    <img x-show="activeImage === index"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        :src="image" :alt="'Foto ' + (index + 1)"
                                        class="absolute inset-0 w-full h-full object-contain md:object-cover"
                                        :loading="index === 0 ? 'eager' : 'lazy'">
                                </template>
                                @if($auction->status === 'sold')
                                    <div class="absolute inset-0 bg-emerald-900/50 flex items-center justify-center">
                                        <div class="text-center transform -rotate-12">
                                            <div
                                                class="inline-block border-4 border-white px-8 md:px-12 py-4 md:py-6 rounded-lg">
                                                <p class="text-white font-black text-3xl md:text-5xl tracking-wider">TERJUAL</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(count($auction->images) > 1)
                                    <button @click="prev()"
                                        class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="next()"
                                        class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif
                                <div
                                    class="absolute bottom-3 md:bottom-4 right-3 md:right-4 bg-black/60 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium backdrop-blur-sm">
                                    <span x-text="activeImage + 1"></span> / {{ count($auction->images) }}
                                </div>
                            </div>
                            @if(count($auction->images) > 1)
                                <div
                                    class="p-3 md:p-4 bg-gray-50 flex gap-2 md:gap-3 justify-start overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                                    @foreach($auction->images as $index => $image)
                                        <button @click="activeImage = {{ $index }}"
                                            :class="activeImage === {{ $index }} ? 'ring-2 ring-emerald-500 ring-offset-2' : 'opacity-60 hover:opacity-100'"
                                            class="flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden transition-all">
                                            <img src="{{ \App\Helpers\StorageHelper::url($image) }}"
                                                class="w-full h-full object-cover" loading="lazy">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                            <div
                                class="aspect-[16/10] md:aspect-[16/9] bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-20 h-20 md:w-24 md:h-24 text-emerald-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-emerald-600 font-medium text-base md:text-lg">Belum ada foto</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Specifications -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span
                                class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Spesifikasi Objek
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Jenis Aset
                                </p>
                                <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->asset_type_label }}
                                </p>
                            </div>
                            @if($auction->certificate_type)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Sertifikat
                                    </p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">
                                        {{ $auction->certificate_type_label }}</p>
                                </div>
                            @endif
                            @if($auction->land_area)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2">Luas
                                        Tanah</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl">
                                        {{ number_format($auction->land_area, 0, ',', '.') }} m²</p>
                                </div>
                            @endif
                            @if($auction->building_area)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2">Luas
                                        Bangunan</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl">
                                        {{ number_format($auction->building_area, 0, ',', '.') }} m²</p>
                                </div>
                            @endif
                            @if($auction->building_condition)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Kondisi Bangunan</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->building_condition }}</p>
                                </div>
                            @endif
                            @if($auction->bedrooms)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2">Kamar
                                        Tidur</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl">{{ $auction->bedrooms }}</p>
                                </div>
                            @endif
                            @if($auction->bathrooms)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2">Kamar
                                        Mandi</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl">{{ $auction->bathrooms }}</p>
                                </div>
                            @endif
                            @if($auction->floors)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                    <p class="text-xs text-blue-600 uppercase tracking-wide font-semibold mb-2">Lantai</p>
                                    <p class="font-bold text-blue-700 text-lg md:text-xl">{{ $auction->floors }}</p>
                                </div>
                            @endif
                            @if($auction->parking_spaces)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                    <p class="text-xs text-blue-600 uppercase tracking-wide font-semibold mb-2">Parkir</p>
                                    <p class="font-bold text-blue-700 text-lg md:text-xl">{{ $auction->parking_spaces }}
                                        mobil</p>
                                </div>
                            @endif
                            @if($auction->year_built)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Tahun
                                        Dibangun</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->year_built }}</p>
                                </div>
                            @endif
                            @if($auction->debtor_name)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Debitur</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->debtor_name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <span
                                class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            Lokasi Objek
                        </h2>
                        <div class="bg-gray-50 rounded-xl p-4 md:p-6">
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                                {{ $auction->full_address ?: $auction->city }}</p>
                            @if($auction->nearby_facilities)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Fasilitas Terdekat:</h4>
                                    <p class="text-gray-700 text-sm md:text-base">
                                        {!! nl2br(e($auction->nearby_facilities)) !!}</p>
                                </div>
                            @endif
                            @if($auction->transportation_access)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Akses Transportasi:</h4>
                                    <p class="text-gray-700 text-sm md:text-base">
                                        {!! nl2br(e($auction->transportation_access)) !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($auction->description)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </span>
                                Deskripsi
                            </h2>
                            <div
                                class="prose prose-gray max-w-none text-gray-700 text-sm md:text-base bg-gray-50 rounded-xl p-4 md:p-6">
                                {!! nl2br(e($auction->description)) !!}</div>
                        </div>
                    @endif

                    <!-- Legal Information -->
                    @if($auction->legal_basis || $auction->court_decision || $auction->debt_amount || $auction->creditor_name || $auction->encumbrance_details)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                Informasi Hukum
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($auction->creditor_name)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5">
                                        <h4 class="font-semibold text-gray-900 mb-2">Nama Kreditur</h4>
                                        <p class="text-gray-700 text-sm md:text-base">{{ $auction->creditor_name }}</p>
                                    </div>
                                @endif
                                @if($auction->encumbrance_details)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5">
                                        <h4 class="font-semibold text-gray-900 mb-2">Hak Tanggungan</h4>
                                        <p class="text-gray-700 text-sm md:text-base">{!! nl2br(e($auction->encumbrance_details)) !!}</p>
                                    </div>
                                @endif
                                @if($auction->legal_basis)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5">
                                        <h4 class="font-semibold text-gray-900 mb-2">Dasar Hukum</h4>
                                        <p class="text-gray-700 text-sm md:text-base">{!! nl2br(e($auction->legal_basis)) !!}
                                        </p>
                                    </div>
                                @endif
                                @if($auction->court_decision)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5">
                                        <h4 class="font-semibold text-gray-900 mb-2">Putusan Pengadilan</h4>
                                        <p class="text-gray-700 text-sm md:text-base">{{ $auction->court_decision }}</p>
                                        @if($auction->court_decision_date)
                                            <p class="text-gray-600 text-xs mt-1">Tanggal:
                                                {{ $auction->court_decision_date->format('d F Y') }}</p>
                                        @endif
                                    </div>
                                @endif
                                @if($auction->debt_amount)
                                    <div class="bg-red-50 rounded-xl p-4 md:p-5 md:col-span-2">
                                        <h4 class="font-semibold text-red-900 mb-2">Jumlah Hutang</h4>
                                        <p class="text-red-700 text-lg md:text-xl font-bold">Rp
                                            {{ number_format($auction->debt_amount, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Viewing Schedule -->
                    @if($auction->viewing_schedule || $auction->viewing_start || $auction->viewing_end)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                                Jadwal Open House
                            </h2>
                            <div class="bg-blue-50 rounded-xl p-4 md:p-6">
                                @if($auction->viewing_start && $auction->viewing_end)
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-blue-900 mb-2">Periode Viewing</h4>
                                        <p class="text-blue-700">{{ $auction->viewing_start->format('d F Y') }} -
                                            {{ $auction->viewing_end->format('d F Y') }}</p>
                                    </div>
                                @endif
                                @if($auction->viewing_schedule)
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-2">Jadwal Detail</h4>
                                        <p class="text-blue-700 text-sm md:text-base">
                                            {!! nl2br(e($auction->viewing_schedule)) !!}</p>
                                    </div>
                                @endif
                                @if($auction->viewing_contact)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <h4 class="font-semibold text-blue-900 mb-2">Kontak Viewing</h4>
                                        <p class="text-blue-700 text-sm md:text-base">{{ $auction->viewing_contact }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Terms & Conditions -->
                    @if($auction->terms_conditions)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                Syarat & Ketentuan
                            </h2>
                            <div
                                class="prose prose-gray prose-sm max-w-none text-gray-700 bg-yellow-50 rounded-xl p-4 md:p-6">
                                {!! nl2br(e($auction->terms_conditions)) !!}</div>
                            @if($auction->special_conditions)
                                <div class="mt-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Ketentuan Khusus</h4>
                                    <div
                                        class="prose prose-gray prose-sm max-w-none text-gray-700 bg-orange-50 rounded-xl p-4 md:p-6">
                                        {!! nl2br(e($auction->special_conditions)) !!}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Documents -->
                    @if($auction->documents && count($auction->documents) > 0)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span
                                    class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                                Dokumen Pendukung
                            </h2>
                            <div class="grid gap-3">
                                @foreach($auction->documents as $doc)
                                    <a href="{{ \App\Helpers\StorageHelper::url($doc['path'] ?? $doc) }}" target="_blank"
                                        class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-colors group">
                                        <svg class="w-8 h-8 md:w-10 md:h-10 text-red-500 mr-3 md:mr-4 flex-shrink-0"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                        <span
                                            class="text-gray-700 group-hover:text-emerald-600 font-medium flex-1 text-sm md:text-base truncate">{{ $doc['name'] ?? basename($doc['path'] ?? $doc) }}</span>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 flex-shrink-0 ml-2"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Price Card - Sticky on desktop -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 lg:sticky lg:top-24 border border-gray-100">
                        <!-- Price -->
                        <div class="text-center mb-6 pb-6 border-b border-gray-100">
                            <p class="text-xs md:text-sm text-gray-500 mb-2 uppercase tracking-wide font-semibold">Harga
                                Limit</p>
                            <p class="text-3xl md:text-4xl font-bold text-emerald-600">
                                {{ $auction->formatted_limit_price }}</p>
                            @if($auction->estimated_price)
                                <p class="text-xs md:text-sm text-gray-500 mt-3">NJOP/Estimasi: <span
                                        class="font-semibold text-gray-700">{{ $auction->formatted_estimated_price }}</span>
                                </p>
                            @endif
                        </div>

                        <!-- Auction Info -->
                        <div class="space-y-4 md:space-y-5 mb-6 md:mb-8">
                            <div class="flex items-start gap-3 md:gap-4">
                                <div
                                    class="w-9 h-9 md:w-10 md:h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal
                                        Lelang</p>
                                    @if($auction->auction_date)
                                        <p class="font-bold text-gray-900 text-sm md:text-base">
                                            {{ $auction->auction_date->translatedFormat('d F Y') }}</p>
                                        <p class="text-xs md:text-sm text-gray-600">
                                            {{ $auction->auction_date->format('H:i') }} WIB</p>
                                    @else
                                        <p class="font-bold text-gray-900 text-sm md:text-base">Belum ditentukan</p>
                                    @endif
                                </div>
                            </div>

                            @if($auction->registration_start || $auction->registration_end)
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div
                                        class="w-9 h-9 md:w-10 md:h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Pendaftaran
                                        </p>
                                        @if($auction->registration_start && $auction->registration_end)
                                            <p class="font-bold text-gray-900 text-sm md:text-base">
                                                {{ $auction->registration_start->format('d M') }} -
                                                {{ $auction->registration_end->format('d M Y') }}</p>
                                        @elseif($auction->registration_end)
                                            <p class="font-bold text-gray-900 text-sm md:text-base">Sampai
                                                {{ $auction->registration_end->format('d M Y') }}</p>
                                        @else
                                            <p class="font-bold text-gray-900 text-sm md:text-base">Belum ditentukan</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($auction->auction_location)
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div
                                        class="w-9 h-9 md:w-10 md:h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tempat Lelang
                                        </p>
                                        <p class="font-bold text-gray-900 text-sm md:text-base">
                                            {{ $auction->auction_location }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($auction->auction_type)
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div
                                        class="w-9 h-9 md:w-10 md:h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jenis Lelang
                                        </p>
                                        <p class="font-bold text-gray-900 text-sm md:text-base">
                                            {{ $auction->auction_type_label }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($auction->auction_method)
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div
                                        class="w-9 h-9 md:w-10 md:h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Metode Lelang
                                        </p>
                                        <p class="font-bold text-gray-900 text-sm md:text-base">
                                            {{ $auction->auction_method }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Countdown Timer -->
                        @if($auction->auction_date && $auction->days_until_auction >= 0)
                            <div
                                class="bg-gradient-to-r from-orange-100 to-red-100 rounded-xl p-4 md:p-5 mb-6 border border-orange-200">
                                <p class="text-xs text-orange-700 font-bold uppercase tracking-wide mb-2">Waktu Tersisa</p>
                                <div class="text-center">
                                    <p class="text-2xl md:text-3xl font-bold text-orange-700">
                                        {{ $auction->time_until_auction }}</p>
                                    <p class="text-xs text-orange-600 mt-1">Sampai lelang dimulai</p>
                                </div>
                            </div>
                        @endif

                        <!-- Deposit -->
                        @php $deposit = $auction->calculated_deposit; @endphp
                        @if($deposit)
                            <div
                                class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4 md:p-5 mb-6 border border-primary-200">
                                <p class="text-xs text-primary-700 font-bold uppercase tracking-wide mb-2">Uang Jaminan</p>
                                <p class="text-xl md:text-2xl font-bold text-primary-700">Rp
                                    {{ number_format($deposit, 0, ',', '.') }}</p>
                                @if($auction->deposit_percentage)
                                    <p class="text-xs text-primary-600 mt-1">({{ $auction->deposit_percentage }}% dari harga
                                        limit)</p>
                                @endif
                            </div>
                        @endif

                        <!-- Bank Info -->
                        @if($auction->bank_name && $auction->account_number)
                            <div class="bg-gray-50 rounded-xl p-4 md:p-5 mb-6 border border-gray-200">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-3">Transfer Jaminan</p>
                                <div class="space-y-2">
                                    <p class="font-bold text-gray-900 text-base md:text-lg">{{ $auction->bank_name }}</p>
                                    @if($auction->bank_branch)
                                        <p class="text-sm text-gray-600">Cabang {{ $auction->bank_branch }}</p>
                                    @endif
                                    <p
                                        class="text-xl md:text-2xl font-mono font-bold text-gray-900 bg-white rounded-lg p-3 border">
                                        {{ $auction->account_number }}</p>
                                    @if($auction->account_holder)
                                        <p class="text-xs md:text-sm text-gray-600">a.n. {{ $auction->account_holder }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Organizer Info -->
                        @if($auction->organizer_name)
                            <div class="border-t border-gray-100 pt-6 mb-6">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-3">Penyelenggara</p>
                                <div class="space-y-2">
                                    <p class="font-bold text-gray-900 text-base md:text-lg">{{ $auction->organizer_name }}
                                    </p>
                                    @if($auction->organizer_type)
                                        <p class="text-sm text-gray-600">{{ $auction->organizer_type }}</p>
                                    @endif
                                    @if($auction->organizer_address)
                                        <p class="text-sm text-gray-600">{{ $auction->organizer_address }}</p>
                                    @endif
                                    <div class="flex flex-col gap-1">
                                        @if($auction->organizer_email)
                                            <a href="mailto:{{ $auction->organizer_email }}"
                                                class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $auction->organizer_email }}
                                            </a>
                                        @endif
                                        @if($auction->organizer_website)
                                            <a href="{{ $auction->organizer_website }}" target="_blank"
                                                class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Website
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Contact -->
                        @if(($auction->contacts && count($auction->contacts) > 0) || $auction->contact_person || $auction->contact_phone || $auction->organizer_phone)
                            <div class="border-t border-gray-100 pt-6 mb-6">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-3">Contact Person</p>
                                <div class="space-y-4">
                                    {{-- Priority: Multiple Contacts --}}
                                    @if($auction->contacts && count($auction->contacts) > 0)
                                        @foreach($auction->contacts as $contact)
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <p class="font-bold text-gray-900 text-base md:text-lg {{ !empty($contact['position']) ? 'mb-1' : 'mb-3' }}">
                                                    {{ $contact['name'] }}
                                                </p>
                                                @if(!empty($contact['position']))
                                                    <p class="text-sm text-gray-600 mb-3">{{ $contact['position'] }}</p>
                                                @endif
                                                @if(!empty($contact['phone']))
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact['phone']) }}"
                                                        class="inline-flex items-center gap-2 w-full justify-center py-2 px-4 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                        </svg>
                                                        WhatsApp: {{ $contact['phone'] }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach

                                    {{-- Fallback: Legacy Single Contact --}}
                                    @elseif($auction->contact_person || $auction->contact_phone)
                                        @if($auction->contact_person)
                                            <div>
                                                <p class="font-bold text-gray-900 text-base md:text-lg">
                                                    {{ $auction->contact_person }}</p>
                                                @if($auction->contact_position)
                                                    <p class="text-sm text-gray-600">{{ $auction->contact_position }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        @if($auction->contact_phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $auction->contact_phone) }}"
                                                class="inline-flex items-center gap-2 w-full justify-center py-3 px-4 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                </svg>
                                                WhatsApp: {{ $auction->contact_phone }}
                                            </a>
                                        @endif
                                    @endif

                                    {{-- Organizer fallback if no other contacts --}}
                                    @if(!($auction->contacts && count($auction->contacts) > 0) && !$auction->contact_person && !$auction->contact_phone && $auction->organizer_phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $auction->organizer_phone) }}"
                                            class="inline-flex items-center gap-2 w-full justify-center py-3 px-4 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                            </svg>
                                            WhatsApp: {{ $auction->organizer_phone }}
                                        </a>
                                    @endif

                                    @if($auction->contact_email)
                                        <a href="mailto:{{ $auction->contact_email }}"
                                            class="inline-flex items-center gap-2 w-full justify-center py-3 px-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Email
                                        </a>
                                    @endif

                                    @if($auction->contact_office_hours)
                                        <div class="text-xs text-gray-600 bg-gray-50 rounded-lg p-3">
                                            <strong>Jam Kerja:</strong> {{ $auction->contact_office_hours }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status -->
                        @if($auction->status === 'sold')
                            <div
                                class="text-center py-4 md:py-5 bg-emerald-100 rounded-xl text-emerald-700 font-bold text-sm md:text-base">
                                <svg class="w-5 h-5 md:w-6 md:h-6 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Objek Telah Terjual
                            </div>
                        @elseif($auction->is_registration_open)
                            <div
                                class="text-center py-4 md:py-5 bg-green-100 rounded-xl text-green-700 font-bold text-sm md:text-base">
                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                Pendaftaran Dibuka
                            </div>
                        @else
                            <div
                                class="text-center py-4 md:py-5 bg-gray-100 rounded-xl text-gray-700 font-bold text-sm md:text-base">
                                {{ $auction->status_label }}
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-3 mt-6">
                            <button onclick="showInterestModal({{ $auction->id }})"
                                class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Nyatakan Minat
                            </button>
                            <button onclick="downloadBrochure()"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Brosur
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Auctions -->
    @if($relatedAuctions->count() > 0)
        <section class="py-12 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Lelang Terkait</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Properti serupa yang mungkin menarik bagi Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($relatedAuctions as $related)
                        <div
                            class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                @if($related->main_image)
                                    <img src="{{ $related->main_image }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary-100 to-emerald-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-primary-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $related->status_color['bg'] }} {{ $related->status_color['text'] }}">
                                        {{ $related->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-2">
                                    <span class="text-sm text-primary-600 font-medium">{{ $related->asset_type_label }}</span>
                                </div>
                                <h3
                                    class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                    <a href="{{ route('auctions.show', $related) }}">{{ $related->title }}</a>
                                </h3>
                                <div class="text-sm text-gray-600 mb-3 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $related->city }}
                                </div>
                                <div class="text-xl font-bold text-emerald-600 mb-4">{{ $related->formatted_limit_price }}</div>
                                <a href="{{ route('auctions.show', $related) }}"
                                    class="block w-full bg-primary-600 hover:bg-primary-700 text-white text-center py-2 px-4 rounded-xl font-semibold transition-all duration-300">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Interest Modal -->
    <div id="interest-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </span>
                        Nyatakan Minat
                    </h3>
                    <button onclick="hideInterestModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="interest-form" method="POST" action="{{ route('auctions.express-interest', $auction) }}"
                    class="space-y-4">
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
                            class="flex-1 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
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
            function showInterestModal(auctionId) {
                document.getElementById('interest-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideInterestModal() {
                document.getElementById('interest-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function shareAuction() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $auction->title }}',
                        text: 'Lihat lelang properti ini: {{ $auction->title }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback to copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        alert('Link berhasil disalin ke clipboard!');
                    });
                }
            }

            function downloadBrochure() {
                // Implement brochure download functionality
                alert('Fitur download brosur akan segera tersedia.');
            }

            // Close modal when clicking outside
            document.getElementById('interest-modal').addEventListener('click', function (e) {
                if (e.target === this) {
                    hideInterestModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    hideInterestModal();
                }
            });
        </script>
    @endpush
</x-frontend-layout>
