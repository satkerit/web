@php
    $currentRoute = request()->route()->getName();
@endphp

<nav x-data="{ 
    mobileOpen: false, 
    scrolled: false,
    searchOpen: false 
}" 
@scroll.window="scrolled = window.scrollY > 20"
:class="scrolled ? 'bg-white/95 backdrop-blur-lg shadow-lg' : 'bg-transparent'"
class="transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    @if($company?->logo)
                        <img src="{{ \App\Helpers\StorageHelper::url($company->logo) }}" 
                             alt="{{ $company->name }}" 
                             class="h-12 w-auto transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">{{ substr($company->name ?? 'BPRS', 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="hidden sm:block">
                        <div class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">
                            Lelang Agunan
                        </div>
                        <div class="text-xs text-gray-600 font-medium">
                            {{ $company->name ?? 'BPRS Bangka Belitung' }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <!-- Main Navigation -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('auctions.index') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl font-medium transition-all duration-300 {{ $currentRoute === 'auctions.index' ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:text-orange-600 hover:bg-orange-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Semua Lelang</span>
                    </a>

                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center space-x-2 px-4 py-2 rounded-xl font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.586-3.586a1 1 0 011.414 0L16 8a2 2 0 012 2v1"/>
                            </svg>
                            <span>Kategori</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 py-2 z-50">
                            <a href="{{ route('auctions.index', ['asset_type' => 'rumah']) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </span>
                                Rumah
                            </a>
                            <a href="{{ route('auctions.index', ['asset_type' => 'tanah']) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </span>
                                Tanah
                            </a>
                            <a href="{{ route('auctions.index', ['asset_type' => 'ruko']) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </span>
                                Ruko
                            </a>
                            <a href="{{ route('auctions.index', ['asset_type' => 'kendaraan']) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM21 17a2 2 0 11-4 0 2 2 0 014 0zM13 2L3 14h9l4-4.5L13 2z"/>
                                    </svg>
                                </span>
                                Kendaraan
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- Search Button -->
                <button @click="searchOpen = !searchOpen" 
                        class="p-2 text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Contact Button -->
                <a href="tel:{{ $company->phone ?? '' }}" 
                   class="btn-auction-primary px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                    Hubungi Kami
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden flex items-center space-x-2">
                <button @click="searchOpen = !searchOpen" 
                        class="p-2 text-gray-600 hover:text-orange-600 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                <button @click="mobileOpen = !mobileOpen" 
                        class="p-2 text-gray-600 hover:text-orange-600 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Search Overlay -->
        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute top-full left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-gray-200 p-4 z-40">
            <form method="GET" action="{{ route('auctions.index') }}" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           id="auction-search"
                           placeholder="Cari lelang agunan..." 
                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-transparent text-lg"
                           value="{{ request('search') }}">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 btn-auction-primary px-6 py-2 rounded-xl font-semibold">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden absolute top-full left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-gray-200 z-40">
            <div class="px-4 py-6 space-y-4">
                <a href="{{ route('auctions.index') }}" 
                   @click="mobileOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium {{ $currentRoute === 'auctions.index' ? 'bg-orange-100 text-orange-700' : 'text-gray-700 hover:bg-orange-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Semua Lelang</span>
                </a>

                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm font-semibold text-gray-500 mb-3 px-4">Kategori Agunan</p>
                    <div class="space-y-2">
                        <a href="{{ route('auctions.index', ['asset_type' => 'rumah']) }}" 
                           @click="mobileOpen = false"
                           class="flex items-center space-x-3 px-4 py-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors">
                            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                            <span>Rumah</span>
                        </a>
                        <a href="{{ route('auctions.index', ['asset_type' => 'tanah']) }}" 
                           @click="mobileOpen = false"
                           class="flex items-center space-x-3 px-4 py-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            <span>Tanah</span>
                        </a>
                        <a href="{{ route('auctions.index', ['asset_type' => 'ruko']) }}" 
                           @click="mobileOpen = false"
                           class="flex items-center space-x-3 px-4 py-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors">
                            <span class="w-2 h-2 bg-purple-400 rounded-full"></span>
                            <span>Ruko</span>
                        </a>
                        <a href="{{ route('auctions.index', ['asset_type' => 'kendaraan']) }}" 
                           @click="mobileOpen = false"
                           class="flex items-center space-x-3 px-4 py-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                            <span>Kendaraan</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <a href="{{ route('home') }}" 
                       @click="mobileOpen = false"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium text-gray-700 hover:bg-orange-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                    
                    <a href="tel:{{ $company->phone ?? '' }}" 
                       @click="mobileOpen = false"
                       class="flex items-center justify-center space-x-2 mt-4 btn-auction-primary px-6 py-3 rounded-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>