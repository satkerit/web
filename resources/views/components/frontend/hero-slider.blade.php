@props(['heroSlides', 'heroSliderDelay' => 5000])

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
<section class="py-12 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div x-data="{
            active: 0,
            total: {{ $heroSlides->count() }},
            autoplay: null,
            delay: {{ $heroSliderDelay }},
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
                            $compressedImage = class_exists('\App\Services\ImageCompressionService') ? \App\Services\ImageCompressionService::getExistingCompressed($slide->image) : $slide->image;
                            $webpImages = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingResponsiveWebP($slide->image) : [];
                            $avifImages = class_exists('\App\Services\WebPConverterService') ? \App\Services\WebPConverterService::getExistingResponsiveAVIF($slide->image) : [];
                        @endphp
                        <picture>
                            {{-- AVIF sources (most modern, smallest size) --}}
                            @if(isset($avifImages['mobile']))
                            <source media="(max-width: 640px)"
                                    srcset="{{ \App\Helpers\StorageHelper::url($avifImages['mobile']) }}"
                                    type="image/avif">
                            @endif
                            @if(isset($avifImages['tablet']))
                            <source media="(max-width: 1024px)"
                                    srcset="{{ \App\Helpers\StorageHelper::url($avifImages['tablet']) }}"
                                    type="image/avif">
                            @endif
                            @if(isset($avifImages['desktop']))
                            <source media="(min-width: 1025px)"
                                    srcset="{{ \App\Helpers\StorageHelper::url($avifImages['desktop']) }}"
                                    type="image/avif">
                            @endif
                            {{-- WebP sources --}}
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

                            {{-- Fallback --}}
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
                        <!-- <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div> -->
                        @endif

                        <!-- Content Overlay - Left side for title/subtitle -->
                        @if(($slide->show_title ?? true) || ($slide->show_subtitle ?? true))
                        <div class="absolute bottom-0 left-0 right-0 pb-4 sm:pb-5 px-4 sm:px-6 md:px-8">
                            <div class="max-w-xl">
                                <!-- Title -->
                                @if(($slide->show_title ?? true) && $slide->title)
                                <h2 class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold tracking-tight text-white mb-1 drop-shadow-lg
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
