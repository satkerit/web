@props(['auctions'])

<!-- Auctions Section -->
@if($auctions->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Lelang Agunan
            </div>
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-4">Informasi Lelang Agunan Terbaru</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Temukan peluang investasi menarik melalui lelang agunan dari BPRS Bangka Belitung</p>
        </div>

        <!-- Auctions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($auctions as $index => $auction)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border-0 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-2">
                <!-- Auction Image -->
                <div class="relative h-48 overflow-hidden">
                    @if($auction->main_image)
                    <x-optimized-image
                         src="{{ $auction->main_image }}"
                         alt="{{ $auction->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                         aspect-ratio="16/9"
                    />
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    @endif

                    <!-- Sold Watermark -->
                    @if($auction->status === 'sold')
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 z-20 pointer-events-none">
                        <div class="transform -rotate-12 bg-red-600/90 text-white px-8 py-2 text-xl md:text-2xl font-black tracking-widest border-4 border-white shadow-2xl uppercase backdrop-blur-sm">
                            TERJUAL
                        </div>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $auction->status === 'published' ? 'bg-blue-500 text-white' : ($auction->status === 'registration_open' ? 'bg-green-500 text-white' : ($auction->status === 'auction_scheduled' ? 'bg-yellow-500 text-white' : 'bg-gray-500 text-white')) }}">
                            {{ $auction->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Auction Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold tracking-tight text-gray-900 mb-3 group-hover:text-amber-600 transition-colors line-clamp-2">
                        {{ $auction->title }}
                    </h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $auction->city }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            @if($auction->auction_date)
                                {{ $auction->auction_date->format('d M Y') }}
                            @else
                                Belum ditentukan
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('auctions.show', $auction->slug) }}"
                       class="inline-flex items-center px-4 py-2 bg-amber-50 text-amber-600 rounded-full font-semibold hover:bg-amber-600 hover:text-white transition-all duration-300 transition-colors">
                        Lihat Detail
                        <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Auctions Button -->
        <div class="text-center mt-12">
            <a href="{{ route('auctions.index') }}"
               class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Lihat Semua Lelang Agunan</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <p class="text-gray-500 text-sm mt-3">Jelajahi {{ $auctions->count() > 0 ? 'lebih banyak' : 'semua' }} lelang agunan yang tersedia</p>
        </div>
    </div>
</section>
@endif
