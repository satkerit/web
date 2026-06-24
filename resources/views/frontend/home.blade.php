<x-frontend-layout>

    @if(isset($firstHeroImage))
    @push('head')
    @php
        $compressedFirstImage = class_exists('\App\Services\ImageCompressionService') ? \App\Services\ImageCompressionService::getExistingCompressed($firstHeroImage) : $firstHeroImage;
        $webpFirst = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingResponsiveWebP($firstHeroImage) : [];
        $avifFirst = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingResponsiveAVIF($firstHeroImage) : [];
        $mainWebPFirst = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingWebP($firstHeroImage) : null;
        $mainAVIFFirst = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingAVIF($firstHeroImage) : null;
    @endphp
    {{-- Preload AVIF for most modern browsers (smallest file size) --}}
    @if($mainAVIFFirst)
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($mainAVIFFirst) }}" fetchpriority="high" type="image/avif">
    @endif
    {{-- Preload WebP for modern browsers --}}
    @if($mainWebPFirst)
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($mainWebPFirst) }}" fetchpriority="high" type="image/webp">
    @endif
    {{-- Fallback preload for older browsers --}}
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($compressedFirstImage) }}" fetchpriority="high" type="image/jpeg">
    {{-- Mobile AVIF preload --}}
    @if(isset($avifFirst['mobile']))
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($avifFirst['mobile']) }}" media="(max-width: 640px)" type="image/avif">
    @endif
    {{-- Mobile WebP preload --}}
    @if(isset($webpFirst['mobile']))
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($webpFirst['mobile']) }}" media="(max-width: 640px)" type="image/webp">
    @endif
    @endpush
    @endif

    <!-- Hero Slider - Multiple Transition Effects -->
    <x-frontend.hero-slider :hero-slides="$heroSlides" :hero-slider-delay="$heroSliderDelay ?? 5000" />

    <!-- Why Choose Us Section -->
    <x-frontend.why-choose-us :why-choose-us-settings="$whyChooseUsSettings" :why-choose-us="$whyChooseUs" />


    <!-- Products Section -->
    <x-frontend.products-section :products="$products" />




    <!-- Stats Section -->
    <x-frontend.stats-section :company-info="$companyInfo" />


    <!-- Auctions Section -->
    <x-frontend.auctions-section :auctions="$auctions" />
<!-- News Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Berita & Artikel
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900">Informasi Terkini</h2>
                </div>
                <a href="{{ route('news.index') }}"
                   class="mt-4 md:mt-0 inline-flex items-center text-primary-600 font-bold hover:text-primary-700 group transition-colors">
                    Lihat Semua Berita
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($news as $index => $item)
                <x-frontend.card
                    :title="$item->title"
                    :subtitle="$item->published_at->format('d M Y')"
                    :image="$item->featured_image ? \App\Helpers\StorageHelper::url($item->featured_image) : null"
                    :href="route('news.show', $item->slug)"
                    :delay="$index * 100"
                >
                    {{ $item->excerpt }}
                </x-frontend.card>
                @empty
                <div class="col-span-3 text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg">Belum ada berita tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>


    {{-- ── LAYANAN PENGADUAN SECTION ── --}}
    <section class="py-16 bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 relative overflow-hidden">

        {{-- Background decorative --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/[0.02] rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

            {{-- Header --}}
            <div class="text-center mb-12 fade-in-section">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm text-white/80 rounded-full text-sm font-medium mb-4 border border-white/10">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Layanan Pengaduan
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                    Kami Siap Mendengar Anda
                </h2>
                <p class="text-slate-400 max-w-xl mx-auto text-base">
                    Sampaikan keluhan, masukan, atau laporan pelanggaran Anda. Setiap pengaduan akan ditangani secara profesional dan rahasia.
                </p>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">

                {{-- Pengaduan Nasabah --}}
                <a href="{{ route('pengaduan-nasabah') }}"
                   class="group relative bg-white/5 hover:bg-white/10 border border-white/10 hover:border-emerald-400/40 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-emerald-500/10 fade-in-section">

                    {{-- Icon --}}
                    <div class="flex items-start gap-5">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-bold text-white">Pengaduan Nasabah</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-400/20 text-emerald-300 border border-emerald-400/20">
                                    Resmi
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Sampaikan keluhan terkait produk, layanan, transaksi, atau fasilitas. Pengaduan Anda akan diproses dalam waktu yang telah ditentukan.
                            </p>
                        </div>
                    </div>

                    {{-- Fitur --}}
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Nomor tiket otomatis
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Konfirmasi via email
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Lampiran dokumen
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Pantau status tiket
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-emerald-400 text-sm font-semibold group-hover:text-emerald-300 transition-colors flex items-center gap-1.5">
                            Ajukan Pengaduan
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                        <span class="text-xs text-slate-500">Gratis & Mudah</span>
                    </div>

                    {{-- Hover glow --}}
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-emerald-500/0 to-teal-500/0 group-hover:from-emerald-500/5 group-hover:to-teal-500/5 transition-all duration-300 pointer-events-none"></div>
                </a>

                {{-- Whistleblowing --}}
                <a href="{{ route('whistleblowing') }}"
                   class="group relative bg-white/5 hover:bg-white/10 border border-white/10 hover:border-amber-400/40 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-500/10 fade-in-section">

                    {{-- Icon --}}
                    <div class="flex items-start gap-5">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-bold text-white">Whistleblowing System</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-400/20 text-amber-300 border border-amber-400/20">
                                    Rahasia
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Laporkan dugaan kecurangan, pelanggaran etika, atau penyalahgunaan wewenang secara anonim dan terlindungi.
                            </p>
                        </div>
                    </div>

                    {{-- Fitur --}}
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Identitas terlindungi
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Bisa anonim
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Ditangani khusus
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Lampiran bukti
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-amber-400 text-sm font-semibold group-hover:text-amber-300 transition-colors flex items-center gap-1.5">
                            Buat Laporan
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                        <span class="text-xs text-slate-500">Aman & Terjamin</span>
                    </div>

                    {{-- Hover glow --}}
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/5 group-hover:to-orange-500/5 transition-all duration-300 pointer-events-none"></div>
                </a>

            </div>

            {{-- Bottom note --}}
            <p class="text-center text-slate-500 text-xs mt-8">
                <svg class="w-3.5 h-3.5 inline-block mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Seluruh pengaduan dijamin kerahasiaannya dan ditangani sesuai ketentuan yang berlaku.
            </p>

        </div>
    </section>
    {{-- ── END LAYANAN PENGADUAN SECTION ── --}}


    <!-- Floating Action Buttons for Complaints -->
    <div class="fixed bottom-20 right-4 sm:bottom-24 sm:right-6 md:bottom-28 md:right-8 lg:bottom-32 lg:right-10 z-40 flex flex-col gap-4" x-data="{ showLabels: false }">
        <!-- Customer Complaint Button -->
        <a href="{{ route('pengaduan-nasabah') }}"
           @mouseenter="showLabels = true"
           @mouseleave="showLabels = false"
           class="group relative flex items-center justify-end">
            <!-- Label -->
            <span class="absolute right-20 px-4 py-2.5 bg-white text-gray-800 text-sm font-semibold rounded-xl shadow-xl whitespace-nowrap transition-all duration-300 opacity-0 group-hover:opacity-100 translate-x-3 group-hover:translate-x-0 border border-emerald-100">
                Pengaduan Nasabah
            </span>
            <!-- Button -->
            <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-full shadow-xl hover:shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer ring-4 ring-white">
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
        </a>

        <!-- Whistleblowing Button -->
        <a href="{{ route('whistleblowing') }}"
           @mouseenter="showLabels = true"
           @mouseleave="showLabels = false"
           class="group relative flex items-center justify-end">
            <!-- Label -->
            <span class="absolute right-20 px-4 py-2.5 bg-white text-gray-800 text-sm font-semibold rounded-xl shadow-xl whitespace-nowrap transition-all duration-300 opacity-0 group-hover:opacity-100 translate-x-3 group-hover:translate-x-0 border border-amber-100">
                Whistleblowing System
            </span>
            <!-- Button -->
            <div class="w-16 h-16 sm:w-[70px] sm:h-[70px] bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 rounded-full shadow-xl hover:shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer ring-4 ring-white">
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </a>

        <!-- Pulse animation for attention -->
        <style>
            @keyframes pulse-ring {
                0% {
                    transform: scale(0.95);
                    opacity: 1;
                }
                50% {
                    transform: scale(1.05);
                    opacity: 0.7;
                }
                100% {
                    transform: scale(0.95);
                    opacity: 1;
                }
            }

            /* Apply pulse animation to buttons */
            .fixed > div > a > div {
                animation: pulse-ring 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Stop animation on hover */
            .fixed > div > a:hover > div {
                animation: none;
            }

            /* Responsive adjustments for mobile */
            @media (max-width: 640px) {
                .fixed > div > a > span {
                    font-size: 0.75rem;
                    padding: 0.5rem 0.75rem;
                }
            }
        </style>
    </div>

    </x-frontend-layout>
