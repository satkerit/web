<x-frontend-layout>
    @if($firstHeroImage)
    @push('head')
    @php
        try {
            $compressedFirstImage = \App\Services\ImageCompressionService::compressForWeb($firstHeroImage, 70, 1920);
            $responsiveFirst = \App\Services\ImageCompressionService::generateResponsiveSizes($firstHeroImage);
            $webpFirst = \App\Services\WebPConverterService::generateResponsiveWebP($firstHeroImage);
            $mainWebPFirst = \App\Services\WebPConverterService::convertToWebP($firstHeroImage, 75);
        } catch (Exception $e) {
            $compressedFirstImage = $firstHeroImage;
            $responsiveFirst = [];
            $webpFirst = [];
            $mainWebPFirst = null;
        }
    @endphp
    {{-- Preload WebP for modern browsers --}}
    @if($mainWebPFirst)
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($mainWebPFirst) }}" fetchpriority="high" type="image/webp">
    @endif
    {{-- Fallback preload for older browsers --}}
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($compressedFirstImage) }}" fetchpriority="high" type="image/jpeg">
    {{-- Mobile WebP preload --}}
    @if(isset($webpFirst['mobile']))
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($webpFirst['mobile']) }}" media="(max-width: 640px)" type="image/webp">
    @endif
    @endpush
    @endif

    <!-- Hero Slider - Multiple Transition Effects -->
    @if($heroSlides->count() > 0)
    @php
        // Prepare slides data with transition settings
        $slidesData = $heroSlides->map(function($slide, $index) {
            return [
                'index' => $index,
                'transitionType' => $slide->transition_type ?? 'fade',
                'transitionDuration' => $slide->transition_duration ?? 500,
            ];
        })->toJson();
    @endphp
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{
                active: 0,
                total: {{ $heroSlides->count() }},
                autoplay: null,
                delay: {{ $heroSliderDelay ?? 5000 }},
                isAnimating: false,
                direction: 'next',
                transitions: {{ $slidesData }},
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.autoplay = setInterval(() => this.next(), this.delay);
                },
                stopAutoplay() {
                    if (this.autoplay) clearInterval(this.autoplay);
                },
                goTo(index) {
                    if (this.isAnimating || index === this.active) return;
                    this.direction = index > this.active ? 'next' : 'prev';
                    this.isAnimating = true;
                    this.active = index;
                    const duration = this.transitions[index]?.transitionDuration || 500;
                    setTimeout(() => { this.isAnimating = false; }, duration);
                    this.stopAutoplay();
                    this.startAutoplay();
                },
                next() {
                    if (this.isAnimating) return;
                    this.goTo((this.active + 1) % this.total);
                },
                prev() {
                    if (this.isAnimating) return;
                    this.goTo((this.active - 1 + this.total) % this.total);
                },
                getTransitionClasses(index) {
                    const isActive = this.active === index;
                    const type = this.transitions[index]?.transitionType || 'fade';

                    if (type === 'fade') {
                        return isActive ? 'opacity-100 z-10' : 'opacity-0 z-0';
                    } else if (type === 'zoom') {
                        return isActive ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-110 z-0';
                    } else if (type === 'slide') {
                        if (isActive) return 'translate-x-0 opacity-100 z-10';
                        return this.direction === 'next' ? 'translate-x-full opacity-0 z-0' : '-translate-x-full opacity-0 z-0';
                    } else if (type === 'flip') {
                        return isActive ? 'opacity-100 [transform:rotateY(0deg)] z-10' : 'opacity-0 [transform:rotateY(90deg)] z-0';
                    } else if (type === 'cube') {
                        if (isActive) return 'opacity-100 z-10 [transform:translateZ(0)_rotateY(0deg)]';
                        return this.direction === 'next'
                            ? 'opacity-0 z-0 [transform:translateZ(-100px)_rotateY(90deg)]'
                            : 'opacity-0 z-0 [transform:translateZ(-100px)_rotateY(-90deg)]';
                    } else if (type === 'cards') {
                        if (isActive) return 'opacity-100 scale-100 translate-x-0 z-10';
                        return this.direction === 'next' ? 'opacity-0 scale-90 translate-x-full z-0' : 'opacity-0 scale-90 -translate-x-full z-0';
                    }
                    return isActive ? 'opacity-100 z-10' : 'opacity-0 z-0';
                },
                getTransitionStyle(index) {
                    const duration = this.transitions[index]?.transitionDuration || 500;
                    return 'transition-duration: ' + duration + 'ms';
                }
            }" class="relative">
                <!-- Slider Container -->
                <div class="relative bg-white rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden group">
                    <!-- Aspect ratio wrapper -->
                    <div class="relative w-full aspect-[16/9] sm:aspect-[18/9] md:aspect-[21/9] lg:aspect-[2.5/1]"
                         style="transform-style: preserve-3d;">
                        @foreach($heroSlides as $index => $slide)
                        <div class="absolute inset-0 w-full h-full transition-all ease-out"
                             :class="getTransitionClasses({{ $index }})"
                             :style="getTransitionStyle({{ $index }})">
                            @if($slide->image)
                            @php
                                try {
                                    $compressedImage = \App\Services\ImageCompressionService::compressForWeb($slide->image, 70, 1920);
                                    $responsiveImages = \App\Services\ImageCompressionService::generateResponsiveSizes($slide->image);
                                    $webpImages = \App\Services\WebPConverterService::generateResponsiveWebP($slide->image);
                                    $mainWebP = \App\Services\WebPConverterService::convertToWebP($slide->image, 75);
                                } catch (Exception $e) {
                                    $compressedImage = $slide->image;
                                    $responsiveImages = [];
                                    $webpImages = [];
                                    $mainWebP = null;
                                }
                            @endphp
                            <picture>
                                {{-- WebP sources for better compression --}}
                                @if(isset($webpImages['mobile']))
                                <source media="(max-width: 640px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($webpImages['mobile']) }}"
                                        type="image/webp">
                                @endif
                                @if(isset($webpImages['tablet']))
                                <source media="(max-width: 1024px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($webpImages['tablet']) }}"
                                        type="image/webp">
                                @endif
                                @if(isset($webpImages['desktop']))
                                <source media="(min-width: 1025px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($webpImages['desktop']) }}"
                                        type="image/webp">
                                @endif

                                {{-- JPEG fallback sources --}}
                                @if(isset($responsiveImages['mobile']))
                                <source media="(max-width: 640px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($responsiveImages['mobile']) }}"
                                        type="image/jpeg">
                                @endif
                                @if(isset($responsiveImages['tablet']))
                                <source media="(max-width: 1024px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($responsiveImages['tablet']) }}"
                                        type="image/jpeg">
                                @endif
                                @if(isset($responsiveImages['desktop']))
                                <source media="(min-width: 1025px)"
                                        srcset="{{ \App\Helpers\StorageHelper::url($responsiveImages['desktop']) }}"
                                        type="image/jpeg">
                                @endif

                                {{-- Final fallback image --}}
                                <img src="{{ \App\Helpers\StorageHelper::url($compressedImage) }}"
                                     alt="{{ $slide->title }}"
                                     class="w-full h-full object-cover object-center hero-slide-img"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                     decoding="{{ $index === 0 ? 'sync' : 'async' }}"
                                     @if($index === 0)
                                     fetchpriority="high"
                                     @endif
                                     width="1920"
                                     height="800">
                            </picture>
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-500 via-primary-600 to-emerald-600 flex items-center justify-center">
                                <span class="text-white text-lg sm:text-xl font-medium px-4 text-center">{{ $slide->title ?? 'Slide ' . ($index + 1) }}</span>
                            </div>
                            @endif

                            <!-- Overlay Gradient - Only show if any content is visible -->
                            @if(($slide->show_title ?? true) || ($slide->show_subtitle ?? true) || (($slide->show_button ?? true) && $slide->link_url))
                            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
                            @endif

                            <!-- Content Overlay - Left side for title/subtitle -->
                            @if(($slide->show_title ?? true) || ($slide->show_subtitle ?? true))
                            <div class="absolute bottom-0 left-0 right-0 pb-4 sm:pb-5 px-4 sm:px-6 md:px-8">
                                <div class="max-w-xl">
                                    <!-- Title -->
                                    @if(($slide->show_title ?? true) && $slide->title)
                                    <h2 class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold text-white mb-1 drop-shadow-md
                                               transform transition-all duration-500 delay-100"
                                        :class="active === {{ $index }} ? 'translate-y-0 opacity-100' : 'translate-y-3 opacity-0'">
                                        {{ $slide->title }}
                                    </h2>
                                    @endif

                                    <!-- Subtitle -->
                                    @if(($slide->show_subtitle ?? true) && $slide->subtitle)
                                    <p class="text-xs sm:text-sm md:text-base text-white/90 line-clamp-2 drop-shadow-sm
                                              transform transition-all duration-500 delay-150"
                                       :class="active === {{ $index }} ? 'translate-y-0 opacity-100' : 'translate-y-3 opacity-0'">
                                        {{ $slide->subtitle }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- CTA Button - Bottom right corner -->
                            @if(($slide->show_button ?? true) && $slide->link_url)
                            <div class="absolute bottom-3 sm:bottom-4 right-3 sm:right-4 md:right-6
                                        transform transition-all duration-500 delay-200"
                                 :class="active === {{ $index }} ? 'translate-y-0 opacity-100' : 'translate-y-3 opacity-0'">
                                <a href="{{ $slide->link_url }}"
                                   class="group/btn inline-flex items-center gap-1.5 px-3 sm:px-4 py-1.5 sm:py-2
                                          bg-white/95 hover:bg-white
                                          text-primary-600 text-xs sm:text-sm font-medium rounded-full
                                          shadow hover:shadow-md
                                          transform hover:scale-105 transition-all duration-200">
                                    <span>{{ $slide->link_text ?? 'Selengkapnya' }}</span>
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Progress Bar -->
                    @if($heroSlides->count() > 1)
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                        <div class="h-full bg-gradient-to-r from-primary-400 to-emerald-400 transition-all duration-300"
                             :style="'width: ' + ((active + 1) / total * 100) + '%'"></div>
                    </div>
                    @endif
                </div>

                <!-- Navigation Arrows -->
                @if($heroSlides->count() > 1)
                <button @click="prev()"
                        aria-label="Previous slide"
                        class="absolute left-2 sm:left-4 md:left-6 top-1/2 -translate-y-1/2
                               w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14
                               bg-white/90 hover:bg-white backdrop-blur-sm
                               rounded-full shadow-lg hover:shadow-xl
                               flex items-center justify-center
                               transition-all duration-300 hover:scale-110
                               opacity-0 group-hover:opacity-100 z-20
                               border border-gray-100">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button @click="next()"
                        aria-label="Next slide"
                        class="absolute right-2 sm:right-4 md:right-6 top-1/2 -translate-y-1/2
                               w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14
                               bg-white/90 hover:bg-white backdrop-blur-sm
                               rounded-full shadow-lg hover:shadow-xl
                               flex items-center justify-center
                               transition-all duration-300 hover:scale-110
                               opacity-0 group-hover:opacity-100 z-20
                               border border-gray-100">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Dot Indicators -->
                <div class="absolute -bottom-6 sm:-bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2 sm:gap-3 z-20">
                    @foreach($heroSlides as $index => $slide)
                    <button @click="goTo({{ $index }})"
                            aria-label="Go to slide {{ $index + 1 }}"
                            class="group/dot relative flex items-center justify-center"
                            :class="active === {{ $index }} ? 'scale-100' : 'scale-90 hover:scale-100'">
                        <!-- Outer ring for active -->
                        <span class="absolute w-6 h-6 sm:w-7 sm:h-7 rounded-full border-2 transition-all duration-300"
                              :class="active === {{ $index }} ? 'border-primary-500 scale-100 opacity-100' : 'border-transparent scale-0 opacity-0'"></span>
                        <!-- Inner dot -->
                        <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full transition-all duration-300 shadow-sm"
                              :class="active === {{ $index }} ? 'bg-primary-500' : 'bg-gray-300 hover:bg-gray-400'"></span>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Stats Section -->
    @php
        $hasStats = $companyInfo && (
            ($companyInfo->stat_years_experience && $companyInfo->stat_years_experience > 0) ||
            ($companyInfo->stat_branch_offices && $companyInfo->stat_branch_offices > 0) ||
            ($companyInfo->stat_total_assets && $companyInfo->stat_total_assets !== 'N/A') ||
            ($companyInfo->stat_cash_offices && $companyInfo->stat_cash_offices > 0) ||
            ($companyInfo->stat_mobile_cash_offices && $companyInfo->stat_mobile_cash_offices > 0)
        );
    @endphp
    @if($hasStats)
    <section class="relative py-16 bg-white overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-xl animate-float"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-teal-500/10 rounded-full blur-xl animate-float-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-blue-500/5 to-emerald-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex flex-wrap justify-center gap-6 lg:gap-10 stagger-container">
                <!-- Tahun Pengalaman -->
                @if($companyInfo->stat_years_experience && $companyInfo->stat_years_experience > 0)
                <div class="stats-card text-center group stagger-item fade-in-section w-36 sm:w-40">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_years_experience }}" data-suffix="+">0+</div>
                    <div class="text-gray-600 text-sm">Tahun Pengalaman</div>
                </div>
                @endif

                <!-- Kantor Cabang -->
                @if($companyInfo->stat_branch_offices && $companyInfo->stat_branch_offices > 0)
                <div class="stats-card text-center group stagger-item fade-in-section delay-100 w-36 sm:w-40">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_branch_offices }}" data-suffix="+">0+</div>
                    <div class="text-gray-600 text-sm">Kantor Cabang</div>
                </div>
                @endif

                <!-- Total Aset -->
                @if($companyInfo->stat_total_assets && $companyInfo->stat_total_assets !== 'N/A')
                <div class="stats-card text-center group stagger-item fade-in-section delay-200 w-36 sm:w-40">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $companyInfo->stat_total_assets }}</div>
                    <div class="text-gray-600 text-sm">Total Aset</div>
                </div>
                @endif

                <!-- Kantor Kas -->
                @if($companyInfo->stat_cash_offices && $companyInfo->stat_cash_offices > 0)
                <div class="stats-card text-center group stagger-item fade-in-section delay-300 w-36 sm:w-40">
                    <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_cash_offices }}" data-suffix="+">0+</div>
                    <div class="text-gray-600 text-sm">Kantor Kas</div>
                </div>
                @endif

                <!-- Kantor Kas Keliling -->
                @if($companyInfo->stat_mobile_cash_offices && $companyInfo->stat_mobile_cash_offices > 0)
                <div class="stats-card text-center group stagger-item fade-in-section delay-400 w-36 sm:w-40">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_mobile_cash_offices }}" data-suffix="+">0+</div>
                    <div class="text-gray-600 text-sm">Kantor Kas Keliling</div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif


    <!-- Products Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50/30 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-in-section">
                <div class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4 animate-bounce-in">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Produk & Layanan
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 animate-fade-in-up delay-200">Solusi Keuangan Syariah Terbaik</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg animate-fade-in-up delay-300">Berbagai produk simpanan dan pembiayaan syariah yang dirancang untuk memenuhi kebutuhan finansial Anda</p>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-container">
                @forelse($products as $index => $product)
                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 stagger-item scale-in" style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Product Image -->
                    <div class="relative aspect-[4/3] overflow-hidden">
                        @if($product->image)
                        <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @endif
                        <span class="absolute top-3 left-3 sm:top-4 sm:left-4 px-2 py-1 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-lg bg-primary-500 text-white shadow-lg">
                            {{ $product->type === 'simpanan_syariah' ? 'Simpanan' : ($product->type === 'deposito' ? 'Deposito' : 'Pembiayaan') }}
                        </span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-900 mb-3 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->short_description }}</p>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn-shine inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition-colors group">
                            Selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16 fade-in-section">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-in">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-gray-500 text-lg">Belum ada produk tersedia</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-12 fade-in-section">
                <a href="{{ route('products.simpanan-syariah') }}" class="btn-shine inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-105">
                    Lihat Semua Produk
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>




    <!-- Why Choose Us Section -->
    @if($whyChooseUsSettings?->is_active)
    <section class="py-20 bg-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl animate-float-delayed"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div class="slide-in-left">
                    <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold mb-4 animate-bounce-in">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Mengapa Memilih Kami
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        {!! $whyChooseUsSettings->section_title ?? 'Bank Syariah yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">Terpercaya</span> dan Amanah' !!}
                    </h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        {{ $whyChooseUsSettings->section_subtitle ?? 'Kami berkomitmen memberikan layanan perbankan syariah terbaik dengan prinsip kehati-hatian dan kepatuhan terhadap syariah Islam.' }}
                    </p>

                    <!-- Features List -->
                    <div class="space-y-6">
                        @forelse($whyChooseUs as $index => $item)
                        <div class="flex items-start group fade-in-section" style="animation-delay: {{ $index * 100 }}ms">
                            <div class="w-12 h-12 {{ $item->bg_class }} rounded-xl flex items-center justify-center mr-4 flex-shrink-0 {{ $item->hover_bg_class }} group-hover:scale-110 transition-all duration-300 shadow-sm">
                                @if($item->icon)
                                <img src="{{ \App\Helpers\StorageHelper::url($item->icon) }}" class="w-6 h-6 object-contain transition-all duration-300 filter group-hover:brightness-0 group-hover:invert" alt="{{ $item->title }}">
                                @else
                                <svg class="w-6 h-6 {{ $item->text_class }} group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1 text-lg">{{ $item->title }}</h4>
                                <p class="text-gray-600 leading-relaxed">{{ $item->description }}</p>
                            </div>
                        </div>
                        @empty
                        <!-- Default content if no dynamic data -->
                        <div class="flex items-start group fade-in-section">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:bg-emerald-500 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Sesuai Prinsip Syariah</h4>
                                <p class="text-gray-600">Seluruh produk dan layanan kami telah disetujui oleh Dewan Pengawas Syariah</p>
                            </div>
                        </div>
                        <div class="flex items-start group fade-in-section delay-100">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:bg-blue-500 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Aman & Terpercaya</h4>
                                <p class="text-gray-600">Diawasi oleh OJK dan dijamin oleh LPS untuk keamanan dana Anda</p>
                            </div>
                        </div>
                        <div class="flex items-start group fade-in-section delay-200">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:bg-amber-500 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Proses Cepat & Mudah</h4>
                                <p class="text-gray-600">Layanan yang efisien dengan proses yang transparan dan mudah dipahami</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Image -->
                <div class="relative slide-in-right">
                    <div class="relative z-10">
                        @if($whyChooseUsSettings->section_image)
                        <img src="{{ \App\Helpers\StorageHelper::url($whyChooseUsSettings->section_image) }}"
                             alt="Why Choose Us"
                             class="rounded-3xl shadow-2xl w-full max-w-md mx-auto lg:max-w-none">
                        @else
                        <div class="rounded-3xl shadow-2xl w-full h-[600px] bg-gradient-to-br from-emerald-400 via-teal-500 to-emerald-600 flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-20"></div>
                            <div class="text-center p-8">
                                <span class="block mb-4 p-4 bg-white/20 rounded-2xl w-24 h-24 mx-auto backdrop-blur-sm shadow-inner">
                                    <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- Decorative Elements -->
                    <div class="absolute -bottom-6 -left-6 w-72 h-72 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-3xl -z-10 opacity-20 animate-pulse-glow"></div>
                    <div class="absolute -top-6 -right-6 w-48 h-48 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-3xl -z-10 opacity-20"></div>

                    <!-- Floating Card -->
                    <div class="absolute -bottom-8 -right-8 bg-white rounded-2xl shadow-xl p-6 z-20 animate-bounce-in delay-500">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ $whyChooseUsSettings->badge_text ?? '100% Syariah Compliant' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


    <!-- News Section -->
    <section class="py-20 bg-gray-50">
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
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Informasi Terkini</h2>
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
                <article class="group bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200 hover:-translate-y-1">
                    <!-- News Image -->
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        @if($item->featured_image)
                        <img src="{{ \App\Helpers\StorageHelper::url($item->featured_image) }}"
                             alt="{{ $item->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                             loading="lazy">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        @endif

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Category -->
                        @if($item->category)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-primary-500 text-white text-xs font-bold rounded-lg shadow-lg">
                            {{ $item->category }}
                        </span>
                        @endif
                    </div>

                    <!-- News Content -->
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $item->published_at->format('d M Y') }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2 h-[3.5rem]">
                            {{ $item->title }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                            {{ $item->excerpt }}
                        </p>
                        <a href="{{ route('news.show', $item->slug) }}"
                           class="inline-flex items-center text-primary-600 font-bold group/link hover:text-primary-700 transition-colors">
                            Baca Selengkapnya
                            <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </article>
                @empty
                <div class="col-span-3 text-center py-16">
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


    <!-- Auctions Section -->
    @if($auctions->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Lelang Agunan
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Informasi Lelang Agunan Terbaru</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan peluang investasi menarik melalui lelang agunan dari BPRS Bangka Belitung</p>
            </div>

            <!-- Auctions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($auctions as $index => $auction)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-amber-200 hover:-translate-y-2">
                    <!-- Auction Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($auction->main_image)
                        <img src="{{ $auction->main_image }}"
                             alt="{{ $auction->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
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
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-amber-600 transition-colors line-clamp-2">
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
                           class="inline-flex items-center text-amber-600 font-semibold group/link hover:text-amber-700 transition-colors">
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
</x-frontend-layout>
