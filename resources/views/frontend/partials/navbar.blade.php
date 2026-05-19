@php
    $company = $company ?? \App\Models\CompanyInfo::getInfo();
@endphp
<style>
    /* Prevent flash of unstyled content before Alpine.js initializes */
    [x-cloak] { display: none !important; }
    /* Disable transitions during page load */
    .no-transition * { transition: none !important; }
</style>
<nav class="relative" x-data="{ scrolled: false, mobileOpen: false, ready: false }" x-init="$nextTick(() => { ready = true; scrolled = window.scrollY > 50 })" @scroll.window="scrolled = window.scrollY > 50">
    <div :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'" class="transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                @if($company?->logo)
                <x-optimized-image 
                    src="{{ \App\Helpers\StorageHelper::url($company->logo) }}" 
                    alt="{{ $company->name ?? 'Logo' }}" 
                    class="h-12 w-auto logo-no-bg group-hover:scale-105 transition-all duration-300"
                    :lazy="false"
                    priority="true"
                />
                @else
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-emerald-500/30 transition-all duration-300 group-hover:scale-105">
                    <span class="text-white font-bold text-lg">B</span>
                </div>
                @endif
                {{-- <div class="hidden sm:block">
                    <span class="text-xl font-bold" :class="scrolled ? 'text-gray-800' : 'text-white'">{{ $company?->name ?? 'BPRS Bangka Belitung' }}</span>
                    <p class="text-xs" :class="scrolled ? 'text-gray-500' : 'text-white/70'">{{ $company?->tagline ?? 'Bank Pembiayaan Rakyat' }}</p>
                </div> --}}
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="nav-link px-4 py-2 rounded-lg font-medium tracking-tight transition-all duration-300" :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'">
                    Beranda
                </a>

                <!-- About Dropdown -->
                <div class="relative" x-data="{ open: false, timeout: null }"
                     @mouseenter="clearTimeout(timeout); open = true"
                     @mouseleave="timeout = setTimeout(() => open = false, 150)">
                    <button class="nav-link px-4 py-2 rounded-lg font-medium tracking-tight transition-all duration-300 inline-flex items-center" :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'">
                        Tentang Kami
                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <!-- Invisible bridge to prevent gap -->
                    <div class="absolute left-0 w-full h-2 top-full"></div>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" class="absolute left-0 top-full pt-1 w-64 z-50">
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 py-3 border border-gray-100">
                        <a href="{{ route('about.company') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <span class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
                            Profil Perusahaan
                        </a>
                        <a href="{{ route('about.komisaris') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                            Dewan Komisaris
                        </a>
                        <a href="{{ route('about.direksi') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg></span>
                            Dewan Direksi
                        </a>
                        <a href="{{ route('about.pengawas-syariah') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                            Dewan Pengawas Syariah
                        </a>
                        <a href="{{ route('about.struktur') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg></span>
                            Struktur Organisasi
                        </a>
                        <a href="{{ route('about.offices') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                            Kantor Kami
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Products Dropdown -->
                <div class="relative" x-data="{ open: false, timeout: null }"
                     @mouseenter="clearTimeout(timeout); open = true"
                     @mouseleave="timeout = setTimeout(() => open = false, 150)">
                    <button class="nav-link px-4 py-2 rounded-lg font-medium tracking-tight transition-all duration-300 inline-flex items-center"
                            :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'"
                            :aria-expanded="open"
                            aria-haspopup="true">
                        Produk & Layanan
                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <!-- Invisible bridge to prevent gap -->
                    <div class="absolute left-0 w-full h-2 top-full"></div>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute left-0 top-full pt-1 w-64 z-50">
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 py-3 border border-gray-100">
                        <a href="{{ route('products.simpanan-syariah') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
                            Simpanan Syariah
                        </a>
                        <a href="{{ route('products.pembiayaan-syariah') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            Pembiayaan Syariah
                        </a>
                        <a href="{{ route('products.deposito-syariah') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            Deposito Syariah
                        </a>
                        <a href="{{ route('products.kas-keliling') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
                            Kas Keliling
                        </a>
                        <div class="border-t border-gray-100 my-2"></div>
                        <a href="{{ route('financing-simulation') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></span>
                            Simulasi Pembiayaan
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Informasi Dropdown (Berita, Lelang, Karir) -->
                <div class="relative" x-data="{ open: false, timeout: null }"
                     @mouseenter="clearTimeout(timeout); open = true"
                     @mouseleave="timeout = setTimeout(() => open = false, 150)">
                    <button class="nav-link px-4 py-2 rounded-lg font-medium transition-all duration-300 inline-flex items-center" :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'">
                        Informasi
                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute left-0 w-full h-2 top-full"></div>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute left-0 top-full pt-1 w-64 z-50">
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 py-3 border border-gray-100">
                            <a href="{{ route('news.index') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></span>
                                Berita & Artikel
                            </a>
                            <a href="{{ route('auctions.index') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg></span>
                                Lelang Agunan
                            </a>
                            <a href="{{ route('brochures.index') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></span>
                                Brosur Pembiayaan
                            </a>
                            <a href="{{ route('careers.index') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                Karir
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pengaduan Dropdown -->
                <div class="relative" x-data="{ open: false, timeout: null }"
                     @mouseenter="clearTimeout(timeout); open = true"
                     @mouseleave="timeout = setTimeout(() => open = false, 150)">
                    <button class="nav-link px-4 py-2 rounded-lg font-medium transition-all duration-300 inline-flex items-center" :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'">
                        Pengaduan
                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute right-0 w-full h-2 top-full"></div>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute right-0 top-full pt-1 w-72 z-50">
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 py-3 border border-gray-100">
                            <a href="{{ route('pengaduan-nasabah') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg></span>
                                Pengaduan Nasabah
                            </a>
                            <a href="{{ route('whistleblowing') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
                                Whistleblowing System
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Reports Dropdown -->
                <div class="relative" x-data="{ open: false, timeout: null }"
                     @mouseenter="clearTimeout(timeout); open = true"
                     @mouseleave="timeout = setTimeout(() => open = false, 150)">
                    <button class="nav-link px-4 py-2 rounded-lg font-medium transition-all duration-300 inline-flex items-center" :class="scrolled ? 'text-gray-800 hover:text-emerald-600 hover:bg-emerald-50' : 'text-gray-800 hover:text-emerald-600 hover:bg-gray-100/30'">
                        Laporan
                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <!-- Invisible bridge to prevent gap -->
                    <div class="absolute right-0 w-full h-2 top-full"></div>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute right-0 top-full pt-1 w-72 z-50">
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 py-3 border border-gray-100">
                        <a href="{{ route('reports.keuangan-publikasi') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            Laporan Keuangan Publikasi
                        </a>
                        <a href="{{ route('reports.tata-kelola') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                            Laporan Tata Kelola
                        </a>
                        <a href="{{ route('reports.tahunan') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
                            Laporan Tahunan
                        </a>
                        <a href="{{ route('reports.tahunan-berkelanjutan') }}" class="flex items-center px-5 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                            <span class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3"><svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                            Laporan Berkelanjutan
                        </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="ml-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all duration-300 hover:scale-105 btn-shine">
                    Hubungi Kami
                </a>
            </div>

            <!-- Mobile menu button -->
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden relative z-50 p-3 sm:p-4 rounded-xl transition-all duration-300 shadow-lg touch-manipulation" :class="scrolled ? 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-500/30' : 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-500/30'" aria-label="Toggle menu" :aria-expanded="mobileOpen">
                <span class="sr-only">Toggle menu</span>
                <div class="w-6 h-5 relative flex flex-col justify-center">
                    <span class="block absolute h-0.5 w-6 bg-white transform transition-all duration-300 ease-in-out" :class="mobileOpen ? 'rotate-45 translate-y-0' : '-translate-y-2'"></span>
                    <span class="block absolute h-0.5 w-6 bg-white transform transition-all duration-300 ease-in-out" :class="mobileOpen ? 'opacity-0' : 'opacity-100'"></span>
                    <span class="block absolute h-0.5 w-6 bg-white transform transition-all duration-300 ease-in-out" :class="mobileOpen ? '-rotate-45 translate-y-0' : 'translate-y-2'"></span>
                </div>
            </button>
        </div>
    </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-cloak x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         @click.away="mobileOpen = false"
         class="lg:hidden fixed inset-x-0 top-20 bottom-0 bg-white shadow-2xl z-40 overflow-y-auto overscroll-contain"
         style="max-height: calc(100vh - 5rem);">

        <!-- Mobile Header -->
        <div class="sticky top-0 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-4 py-3 sm:py-4 shadow-sm z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div>
                        <h3 class="font-semibold text-base sm:text-lg text-gray-800">Menu</h3>
                        <p class="text-gray-500 text-xs sm:text-sm">{{ $company?->name ?? 'BPRS' }}</p>
                    </div>
                </div>
                <button @click="mobileOpen = false" class="p-2 sm:p-2.5 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors touch-manipulation" aria-label="Close menu">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Content -->
        <div class="p-3 sm:p-4 space-y-2 sm:space-y-3 pb-20">
            <!-- Home -->
            <a href="{{ route('home') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-3 sm:py-3.5 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-all duration-200 group touch-manipulation active:scale-98">
                <span class="w-10 h-10 sm:w-11 sm:h-11 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-emerald-200 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </span>
                <div>
                    <div class="font-medium text-sm sm:text-base">Beranda</div>
                    <div class="text-xs text-gray-500">Halaman utama</div>
                </div>
            </a>

            <!-- Tentang Kami Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 sm:px-4 py-3 sm:py-3.5 text-gray-800 hover:bg-emerald-50 rounded-xl font-medium transition-all duration-200 group touch-manipulation active:scale-98">
                    <div class="flex items-center">
                        <span class="w-10 h-10 sm:w-11 sm:h-11 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <div class="text-left">
                            <div class="font-medium text-sm sm:text-base">Tentang Kami</div>
                            <div class="text-xs text-gray-500">Profil perusahaan</div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="ml-11 sm:ml-13 space-y-1 mt-2 pl-3 sm:pl-4 border-l-2 border-emerald-200">
                    <a href="{{ route('about.company') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Profil Perusahaan
                    </a>
                    <a href="{{ route('about.komisaris') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Dewan Komisaris
                    </a>
                    <a href="{{ route('about.direksi') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Dewan Direksi
                    </a>
                    <a href="{{ route('about.pengawas-syariah') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Dewan Pengawas Syariah
                    </a>
                    <a href="{{ route('about.struktur') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Struktur Organisasi
                    </a>
                    <a href="{{ route('about.offices') }}" @click="mobileOpen = false" class="flex items-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors touch-manipulation active:scale-98">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 sm:mr-3 flex-shrink-0"></span>
                        Kantor Kami
                    </a>
                </div>
            </div>

            <!-- Produk & Layanan Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-gray-800 hover:bg-emerald-50 rounded-xl font-medium transition-all duration-200 group">
                    <div class="flex items-center">
                        <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <div class="text-left">
                            <div class="font-medium">Produk & Layanan</div>
                            <div class="text-xs text-gray-500">Layanan kami</div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="ml-13 space-y-1 mt-2 pl-4 border-l-2 border-purple-200">
                    <a href="{{ route('products.simpanan-syariah') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                        Simpanan Syariah
                    </a>
                    <a href="{{ route('products.pembiayaan-syariah') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                        Pembiayaan Syariah
                    </a>
                    <a href="{{ route('products.deposito-syariah') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                        Deposito Syariah
                    </a>
                    <a href="{{ route('products.kas-keliling') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                        Kas Keliling
                    </a>
                    <a href="{{ route('financing-simulation') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-teal-400 rounded-full mr-3"></span>
                        Simulasi Pembiayaan
                    </a>
                </div>
            </div>

            <!-- Informasi Dropdown (Berita, Lelang, Karir) -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-gray-800 hover:bg-emerald-50 rounded-xl font-medium transition-all duration-200 group">
                    <div class="flex items-center">
                        <span class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-cyan-200 transition-colors">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div class="text-left">
                            <div class="font-medium">Informasi</div>
                            <div class="text-xs text-gray-500">Berita, Lelang, Karir</div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="ml-13 space-y-1 mt-2 pl-4 border-l-2 border-cyan-200">
                    <a href="{{ route('news.index') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-cyan-400 rounded-full mr-3"></span>
                        Berita & Artikel
                    </a>
                    <a href="{{ route('auctions.index') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-amber-400 rounded-full mr-3"></span>
                        Lelang Agunan
                    </a>
                    <a href="{{ route('brochures.index') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-pink-400 rounded-full mr-3"></span>
                        Brosur Pembiayaan
                    </a>
                    <a href="{{ route('careers.index') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-3"></span>
                        Karir
                    </a>
                </div>
            </div>

            <!-- Pengaduan Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-gray-800 hover:bg-emerald-50 rounded-xl font-medium transition-all duration-200 group">
                    <div class="flex items-center">
                        <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 transition-colors">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div class="text-left">
                            <div class="font-medium">Pengaduan</div>
                            <div class="text-xs text-gray-500">Layanan pengaduan</div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="ml-13 space-y-1 mt-2 pl-4 border-l-2 border-red-200">
                    <a href="{{ route('pengaduan-nasabah') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-3"></span>
                        Pengaduan Nasabah
                    </a>
                    <a href="{{ route('whistleblowing') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-red-400 rounded-full mr-3"></span>
                        Whistleblowing System
                    </a>
                </div>
            </div>

            <!-- Laporan Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-gray-800 hover:bg-emerald-50 rounded-xl font-medium transition-all duration-200 group">
                    <div class="flex items-center">
                        <span class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-teal-200 transition-colors">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div class="text-left">
                            <div class="font-medium">Laporan</div>
                            <div class="text-xs text-gray-500">Dokumen laporan</div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="ml-13 space-y-1 mt-2 pl-4 border-l-2 border-teal-200">
                    <a href="{{ route('reports.keuangan-publikasi') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-teal-400 rounded-full mr-3"></span>
                        Laporan Keuangan Publikasi
                    </a>
                    <a href="{{ route('reports.tata-kelola') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-teal-400 rounded-full mr-3"></span>
                        Laporan Tata Kelola
                    </a>
                    <a href="{{ route('reports.tahunan') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-teal-400 rounded-full mr-3"></span>
                        Laporan Tahunan
                    </a>
                    <a href="{{ route('reports.tahunan-berkelanjutan') }}" @click="mobileOpen = false" class="flex items-center px-4 py-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                        <span class="w-2 h-2 bg-teal-400 rounded-full mr-3"></span>
                        Laporan Berkelanjutan
                    </a>
                </div>
            </div>

            <!-- Hubungi Kami CTA -->
            <div class="pt-3 sm:pt-4 border-t border-gray-200 mt-3 sm:mt-4">
                <a href="{{ route('contact') }}" @click="mobileOpen = false" class="block w-full px-4 py-3.5 sm:py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-center rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 active:scale-98 touch-manipulation">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm sm:text-base">Hubungi Kami</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>
