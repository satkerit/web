@props([
    'slides' => [],
    'autoplay' => true,
    'interval' => 5000,
    'showNavigation' => true,
    'showDots' => true,
    'height' => 'auto',
    'transitionType' => 'slide',
    'transitionDuration' => 500
])

@php
    // Get transition settings from first slide if available
    $firstSlide = collect($slides)->first();
    $transitionType = $firstSlide['transition_type'] ?? $transitionType;
    $transitionDuration = $firstSlide['transition_duration'] ?? $transitionDuration;
@endphp

<div class="hero-slider-container"
     data-autoplay="{{ $autoplay ? 'true' : 'false' }}"
     data-interval="{{ $interval }}"
     data-transition-type="{{ $transitionType }}"
     data-transition-duration="{{ $transitionDuration }}"
     style="height: {{ $height }}"
     role="region"
     aria-label="Hero Slider"
     aria-roledescription="carousel">

    @if(count($slides) > 0)
        <div class="hero-slides transition-{{ $transitionType }}" id="heroSlides">
            @foreach($slides as $index => $slide)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                    <!-- Responsive Image -->
                    <picture class="hero-slider">
                        @if(isset($slide['images']['desktop_large']))
                            <source media="(min-width: 1920px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_large']['webp']) }}"
                                    type="image/webp">
                            <source media="(min-width: 1920px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_large']['jpg']) }}">
                        @endif

                        @if(isset($slide['images']['desktop_medium']))
                            <source media="(min-width: 1440px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_medium']['webp']) }}"
                                    type="image/webp">
                            <source media="(min-width: 1440px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_medium']['jpg']) }}">
                        @endif

                        @if(isset($slide['images']['desktop_small']))
                            <source media="(min-width: 1024px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_small']['webp']) }}"
                                    type="image/webp">
                            <source media="(min-width: 1024px)"
                                    srcset="{{ Storage::url($slide['images']['desktop_small']['jpg']) }}">
                        @endif

                        @if(isset($slide['images']['tablet']))
                            <source media="(min-width: 768px)"
                                    srcset="{{ Storage::url($slide['images']['tablet']['webp']) }}"
                                    type="image/webp">
                            <source media="(min-width: 768px)"
                                    srcset="{{ Storage::url($slide['images']['tablet']['jpg']) }}">
                        @endif

                        @if(isset($slide['images']['mobile_large']))
                            <source media="(min-width: 480px)"
                                    srcset="{{ Storage::url($slide['images']['mobile_large']['webp']) }}"
                                    type="image/webp">
                            <source media="(min-width: 480px)"
                                    srcset="{{ Storage::url($slide['images']['mobile_large']['jpg']) }}">
                        @endif

                        @if(isset($slide['images']['mobile_small']))
                            <source srcset="{{ Storage::url($slide['images']['mobile_small']['webp']) }}"
                                    type="image/webp">
                            <img src="{{ Storage::url($slide['images']['mobile_small']['jpg']) }}"
                                 alt="{{ $slide['title'] ?? 'Hero Slide' }}"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        @endif
                    </picture>

                    <!-- Content Overlay -->
                    @if(isset($slide['title']) || isset($slide['subtitle']) || isset($slide['cta_text']))
                        <div class="hero-overlay">
                            <div class="hero-content">
                                @if(isset($slide['title']))
                                    <h1 class="hero-title">{{ $slide['title'] }}</h1>
                                @endif

                                @if(isset($slide['subtitle']))
                                    <p class="hero-subtitle">{{ $slide['subtitle'] }}</p>
                                @endif

                                @if(isset($slide['cta_text']) && isset($slide['cta_url']))
                                    <a href="{{ $slide['cta_url'] }}" class="hero-cta">
                                        {{ $slide['cta_text'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Navigation Arrows -->
        @if($showNavigation && count($slides) > 1)
            <button class="hero-nav prev" onclick="heroSlider.prev()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>
            <button class="hero-nav next" onclick="heroSlider.next()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
            </button>
        @endif

        <!-- Dots Indicator -->
        @if($showDots && count($slides) > 1)
            <div class="hero-dots">
                @foreach($slides as $index => $slide)
                    <button class="hero-dot {{ $index === 0 ? 'active' : '' }}"
                            onclick="heroSlider.goTo({{ $index }})"></button>
                @endforeach
            </div>
        @endif

    @else
        <!-- Placeholder jika tidak ada slides -->
        <div class="hero-slider">
            <div class="hero-overlay">
                <div class="hero-content">
                    <h1 class="hero-title">Welcome</h1>
                    <p class="hero-subtitle">Upload hero images to get started</p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
class HeroSlider {
    constructor(container) {
        this.container = container;
        this.slides = container.querySelector('.hero-slides');
        this.slideElements = container.querySelectorAll('.hero-slide');
        this.dots = container.querySelectorAll('.hero-dot');
        this.currentSlide = 0;
        this.totalSlides = this.slideElements.length;
        this.autoplay = container.dataset.autoplay === 'true';
        this.interval = parseInt(container.dataset.interval) || 5000;
        this.transitionType = container.dataset.transitionType || 'slide';
        this.transitionDuration = parseInt(container.dataset.transitionDuration) || 500;
        this.autoplayTimer = null;
        this.isAnimating = false;

        this.init();
    }

    init() {
        if (this.totalSlides <= 1) return;

        // Set CSS variable for transition duration
        this.container.style.setProperty('--transition-duration', `${this.transitionDuration}ms`);

        // Initialize slides based on transition type
        this.initTransition();

        // Start autoplay
        if (this.autoplay) {
            this.startAutoplay();
        }

        // Pause autoplay on hover
        this.container.addEventListener('mouseenter', () => this.stopAutoplay());
        this.container.addEventListener('mouseleave', () => {
            if (this.autoplay) this.startAutoplay();
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prev();
            if (e.key === 'ArrowRight') this.next();
        });

        // Touch/swipe support
        this.addTouchSupport();
    }

    initTransition() {
        // Add transition class to container
        this.slides.classList.add(`transition-${this.transitionType}`);

        // Initialize all slides
        this.slideElements.forEach((slide, index) => {
            slide.style.setProperty('--transition-duration', `${this.transitionDuration}ms`);
            if (index === 0) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });
    }

    goTo(index) {
        if (index < 0 || index >= this.totalSlides || this.isAnimating) return;
        if (index === this.currentSlide) return;

        this.isAnimating = true;
        const direction = index > this.currentSlide ? 'next' : 'prev';
        const currentSlideEl = this.slideElements[this.currentSlide];
        const nextSlideEl = this.slideElements[index];

        // Apply transition based on type
        switch (this.transitionType) {
            case 'fade':
                this.fadeTransition(currentSlideEl, nextSlideEl);
                break;
            case 'zoom':
                this.zoomTransition(currentSlideEl, nextSlideEl);
                break;
            case 'flip':
                this.flipTransition(currentSlideEl, nextSlideEl, direction);
                break;
            case 'cube':
                this.cubeTransition(currentSlideEl, nextSlideEl, direction);
                break;
            case 'cards':
                this.cardsTransition(currentSlideEl, nextSlideEl, direction);
                break;
            default:
                this.slideTransition(index);
        }

        // Update dots
        if (this.dots.length > 0) {
            this.dots[this.currentSlide].classList.remove('active');
            this.dots[index].classList.add('active');
        }

        this.currentSlide = index;

        // Reset animation flag after transition
        setTimeout(() => {
            this.isAnimating = false;
        }, this.transitionDuration);

        // Restart autoplay
        if (this.autoplay) {
            this.stopAutoplay();
            this.startAutoplay();
        }
    }

    slideTransition(index) {
        this.slides.style.transform = `translateX(-${index * 100}%)`;
        this.slideElements[this.currentSlide].classList.remove('active');
        this.slideElements[index].classList.add('active');
    }

    fadeTransition(currentEl, nextEl) {
        currentEl.classList.add('fade-out');
        nextEl.classList.add('fade-in', 'active');

        setTimeout(() => {
            currentEl.classList.remove('active', 'fade-out');
            nextEl.classList.remove('fade-in');
        }, this.transitionDuration);
    }

    zoomTransition(currentEl, nextEl) {
        currentEl.classList.add('zoom-out');
        nextEl.classList.add('zoom-in', 'active');

        setTimeout(() => {
            currentEl.classList.remove('active', 'zoom-out');
            nextEl.classList.remove('zoom-in');
        }, this.transitionDuration);
    }

    flipTransition(currentEl, nextEl, direction) {
        const flipClass = direction === 'next' ? 'flip-out-left' : 'flip-out-right';
        const flipInClass = direction === 'next' ? 'flip-in-right' : 'flip-in-left';

        currentEl.classList.add(flipClass);
        nextEl.classList.add(flipInClass, 'active');

        setTimeout(() => {
            currentEl.classList.remove('active', flipClass);
            nextEl.classList.remove(flipInClass);
        }, this.transitionDuration);
    }

    cubeTransition(currentEl, nextEl, direction) {
        const cubeClass = direction === 'next' ? 'cube-out-left' : 'cube-out-right';
        const cubeInClass = direction === 'next' ? 'cube-in-right' : 'cube-in-left';

        this.slides.classList.add('cube-perspective');
        currentEl.classList.add(cubeClass);
        nextEl.classList.add(cubeInClass, 'active');

        setTimeout(() => {
            currentEl.classList.remove('active', cubeClass);
            nextEl.classList.remove(cubeInClass);
            this.slides.classList.remove('cube-perspective');
        }, this.transitionDuration);
    }

    cardsTransition(currentEl, nextEl, direction) {
        const cardClass = direction === 'next' ? 'card-out-left' : 'card-out-right';
        const cardInClass = direction === 'next' ? 'card-in-right' : 'card-in-left';

        currentEl.classList.add(cardClass);
        nextEl.classList.add(cardInClass, 'active');

        setTimeout(() => {
            currentEl.classList.remove('active', cardClass);
            nextEl.classList.remove(cardInClass);
        }, this.transitionDuration);
    }

    next() {
        const nextIndex = (this.currentSlide + 1) % this.totalSlides;
        this.goTo(nextIndex);
    }

    prev() {
        const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.goTo(prevIndex);
    }

    startAutoplay() {
        this.autoplayTimer = setInterval(() => {
            this.next();
        }, this.interval);
    }

    stopAutoplay() {
        if (this.autoplayTimer) {
            clearInterval(this.autoplayTimer);
            this.autoplayTimer = null;
        }
    }

    addTouchSupport() {
        let startX = 0;
        let endX = 0;

        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        this.container.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) { // Minimum swipe distance
                if (diff > 0) {
                    this.next(); // Swipe left - next slide
                } else {
                    this.prev(); // Swipe right - prev slide
                }
            }
        });
    }
}

// Initialize hero slider
document.addEventListener('DOMContentLoaded', function() {
    const heroContainer = document.querySelector('.hero-slider-container');
    if (heroContainer) {
        window.heroSlider = new HeroSlider(heroContainer);
    }
});

// Lazy loading untuk images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback untuk browser lama
        images.forEach(img => img.classList.add('loaded'));
    }
});
</script>
@endpush
