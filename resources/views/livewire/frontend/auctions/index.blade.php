<div>
    <!-- Hero Section -->
    <section class="relative pt-32 pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-700 via-primary-500 to-primary-600">
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-teal-300/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-5 py-2.5 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-semibold mb-8 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lelang Aset Agunan
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                Informasi <span class="text-primary-200">Lelang</span>
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto leading-relaxed">
                Temukan peluang investasi menarik melalui lelang aset dengan harga terbaik dan proses yang transparan
            </p>

            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-6 mt-10">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4 text-white">
                    <p class="text-3xl font-bold">{{ \App\Models\Auction::whereIn('status', ['upcoming', 'ongoing'])->count() }}</p>
                    <p class="text-sm text-white/80">Lelang Aktif</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4 text-white">
                    <p class="text-3xl font-bold">{{ \App\Models\Auction::where('status', 'sold')->count() }}</p>
                    <p class="text-sm text-white/80">Sudah Terjual</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 -mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search & Filter Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8 mb-12 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan judul atau lokasi..." class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-gray-900 placeholder-gray-400">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select wire:model.live="statusFilter" class="px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-gray-700 min-w-[160px]">
                            <option value="">Semua Status</option>
                            <option value="upcoming">Akan Datang</option>
                            <option value="ongoing">Berlangsung</option>
                            <option value="sold">Terjual</option>
                            <option value="closed">Selesai</option>
                        </select>
                        <select wire:model.live="assetType" class="px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-gray-700 min-w-[160px]">
                            <option value="">Semua Jenis</option>
                            @foreach(\App\Models\Auction::$assetTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Active Filters -->
                @if($search || $statusFilter || $assetType)
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <span class="text-sm text-gray-500">Filter aktif:</span>
                    @if($search)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="hover:text-emerald-900">&times;</button>
                        </span>
                    @endif
                    @if($statusFilter)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                            {{ \App\Models\Auction::$statusLabels[$statusFilter] ?? $statusFilter }}
                            <button wire:click="$set('statusFilter', '')" class="hover:text-emerald-900">&times;</button>
                        </span>
                    @endif
                    @if($assetType)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                            {{ \App\Models\Auction::$assetTypes[$assetType] ?? $assetType }}
                            <button wire:click="$set('assetType', '')" class="hover:text-emerald-900">&times;</button>
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Loading State -->
            <div wire:loading.flex class="justify-center py-16">
                <div class="flex flex-col items-center gap-4 text-primary-600">
                    <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                    <span class="font-semibold text-lg">Memuat data lelang...</span>
                </div>
            </div>

            <!-- Auctions Grid -->
            <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($auctions as $auction)
                <article class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary-200 hover:-translate-y-1" wire:key="auction-{{ $auction->id }}">
                    <!-- Image Container -->
                    <div class="relative h-64 overflow-hidden">
                        @if($auction->images && count($auction->images) > 0)
                            <img src="{{ Storage::url($auction->images[0]) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-400 via-primary-300 to-primary-500 flex items-center justify-center">
                                <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4 z-10">
                            @if($auction->status === 'upcoming')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-500 text-white text-sm font-bold rounded-xl shadow-lg">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    Akan Datang
                                </span>
                            @elseif($auction->status === 'ongoing')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-500 text-white text-sm font-bold rounded-xl shadow-lg">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    Berlangsung
                                </span>
                            @elseif($auction->status === 'sold')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-500 text-white text-sm font-bold rounded-xl shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Terjual
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-bold rounded-xl shadow-lg">
                                    Selesai
                                </span>
                            @endif
                        </div>

                        <!-- Asset Type Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-white/95 backdrop-blur-sm text-gray-700 text-xs font-semibold rounded-lg shadow">
                                {{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}
                            </span>
                        </div>

                        <!-- Sold Overlay -->
                        @if($auction->status === 'sold')
                            <div class="absolute inset-0 bg-primary-900/70 flex items-center justify-center z-20">
                                <div class="text-center transform -rotate-12">
                                    <div class="inline-block border-4 border-white px-8 py-4 rounded-lg">
                                        <p class="text-white font-black text-3xl tracking-wider">TERJUAL</p>
                                        @if($auction->winning_bid)
                                            <p class="text-primary-200 text-sm mt-1">Rp {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Price on Image -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-white/80 text-xs font-medium mb-1">Harga Limit</p>
                            <p class="text-2xl font-bold text-white drop-shadow-lg">Rp {{ number_format($auction->starting_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Title -->
                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors min-h-[56px]">
                            {{ $auction->title }}
                        </h3>

                        <!-- Info Grid -->
                        <div class="space-y-3 mb-5">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600 line-clamp-2">{{ $auction->location }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $auction->auction_date->translatedFormat('d F Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $auction->auction_date->format('H:i') }} WIB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Specs -->
                        @if($auction->land_area || $auction->building_area)
                        <div class="flex gap-4 mb-5 py-3 border-y border-gray-100">
                            @if($auction->land_area)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/>
                                </svg>
                                <span class="text-sm text-gray-600">LT: {{ number_format($auction->land_area) }}m²</span>
                            </div>
                            @endif
                            @if($auction->building_area)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                                <span class="text-sm text-gray-600">LB: {{ number_format($auction->building_area) }}m²</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('auctions.show', $auction->slug) }}" class="block w-full text-center py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:scale-[1.02]">
                            Lihat Detail
                            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
                @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-lg">
                        <div class="w-32 h-32 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-16 h-16 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Tidak Ada Lelang Ditemukan</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">Belum ada lelang yang sesuai dengan pencarian Anda.</p>
                        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('assetType', '')" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($auctions->hasPages())
            <div class="mt-12">
                {{ $auctions->links() }}
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-600 to-primary-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Tertarik dengan Lelang Kami?</h2>
            <p class="text-white/90 text-lg mb-8">Hubungi kami untuk informasi lebih lanjut tentang prosedur dan persyaratan lelang</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-600 font-bold rounded-xl hover:bg-primary-50 transition-colors shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Hubungi Kami
            </a>
        </div>
    </section>
</div>
