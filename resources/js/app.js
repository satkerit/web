/**
 * Frontend Application - Optimized
 * Lazy loads heavy libraries and uses efficient event handling
 */

import "./bootstrap";

// Alpine.js - Core functionality
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

// Initialize Alpine only if not already provided by Livewire
if (!window.Alpine) {
    window.Alpine = Alpine;
}

window.Alpine.plugin(collapse);

// Register repeaterField component for dynamic form fields
window.Alpine.data("repeaterField", (initialData = []) => ({
    items: [],

    init() {
        const data = Array.isArray(initialData) ? initialData : [];
        this.items = data.map((value) => ({
            value: value || "",
            id:
                crypto.randomUUID?.() ||
                Math.random().toString(36).slice(2, 11),
        }));

        if (this.items.length === 0) {
            this.addItem();
        }
    },

    addItem() {
        this.items.push({
            value: "",
            id:
                crypto.randomUUID?.() ||
                Math.random().toString(36).slice(2, 11),
        });
    },

    removeItem(index) {
        if (this.items.length > 1) {
            this.items.splice(index, 1);
        }
    },
}));

// Start Alpine only if not already started by Livewire
if (!window.Alpine._started) {
    window.Alpine.start();
}

// Lazy load SweetAlert2 only when needed
let SwalPromise = null;
window.Swal = new Proxy(
    {},
    {
        get(_, prop) {
            if (!SwalPromise) {
                SwalPromise = import("sweetalert2").then((module) => {
                    window.Swal = module.default;
                    return module.default;
                });
            }
            return async (...args) => {
                const Swal = await SwalPromise;
                return Swal[prop](...args);
            };
        },
    }
);

// Lazy load Swiper only when hero slider exists
const initHeroSlider = async () => {
    const heroSwiperEl = document.querySelector(".hero-swiper");
    if (!heroSwiperEl) return;

    const [
        { default: Swiper },
        { Navigation, Pagination, Autoplay, EffectFade },
    ] = await Promise.all([import("swiper"), import("swiper/modules")]);

    // Load Swiper CSS dynamically
    const swiperStyles = [
        "swiper/css",
        "swiper/css/navigation",
        "swiper/css/pagination",
        "swiper/css/effect-fade",
    ];

    await Promise.all(swiperStyles.map((style) => import(style)));

    new Swiper(".hero-swiper", {
        modules: [Navigation, Pagination, Autoplay, EffectFade],
        effect: "fade",
        fadeEffect: { crossFade: true },
        speed: 800,
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: ".hero-swiper-pagination",
            clickable: true,
            renderBullet: (_, className) =>
                `<span class="${className}"><span class="progress"></span></span>`,
        },
        navigation: {
            nextEl: ".hero-swiper-next",
            prevEl: ".hero-swiper-prev",
        },
        on: {
            init() {
                this.el.classList.add("swiper-initialized");
            },
            slideChangeTransitionStart() {
                const content =
                    this.slides[this.activeIndex]?.querySelector(
                        ".slide-content"
                    );
                if (content) {
                    content.classList.remove("animate-in");
                    requestAnimationFrame(() =>
                        content.classList.add("animate-in")
                    );
                }
            },
        },
    });
};

// Progressive Image Loading with blur-up effect
const initProgressiveImages = () => {
    // Native lazy loading is supported, but we add blur-up effect
    const images = document.querySelectorAll('img[loading="lazy"]');

    images.forEach((img) => {
        // Skip if already loaded
        if (img.complete && img.naturalHeight !== 0) {
            img.classList.add("loaded");
            return;
        }

        // Add loading class
        img.classList.add("img-loading");

        img.addEventListener(
            "load",
            () => {
                img.classList.remove("img-loading");
                img.classList.add("loaded");
            },
            { once: true }
        );

        img.addEventListener(
            "error",
            () => {
                img.classList.remove("img-loading");
                img.classList.add("img-error");
            },
            { once: true }
        );
    });
};

// Optimized Intersection Observer for scroll animations
const initScrollAnimations = () => {
    const animatedElements = document.querySelectorAll(
        ".fade-in-section, .slide-in-left, .slide-in-right, .scale-in, .stats-counter, .reveal-on-scroll"
    );

    if (!animatedElements.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                el.classList.add("is-visible");

                // Counter animation
                if (el.classList.contains("stats-counter")) {
                    animateCounter(el);
                }

                // Reveal animation
                if (el.classList.contains("reveal-on-scroll")) {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                }

                observer.unobserve(el);
            });
        },
        { threshold: 0.1, rootMargin: "0px 0px -50px 0px" }
    );

    animatedElements.forEach((el) => {
        if (el.classList.contains("reveal-on-scroll")) {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        }
        observer.observe(el);
    });
};

// Counter Animation - optimized with requestAnimationFrame
const animateCounter = (element) => {
    const target = parseInt(element.dataset.target, 10);
    const suffix = element.dataset.suffix || "";
    const duration = 2000;
    const startTime = performance.now();

    const updateCounter = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const current = Math.floor(target * easeOutQuart);

        element.textContent = current + suffix;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    };

    requestAnimationFrame(updateCounter);
};

// Staggered Animation for Cards - optimized
const initStaggeredAnimations = () => {
    document.querySelectorAll(".stagger-container").forEach((container) => {
        container.querySelectorAll(".stagger-item").forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
};

// Throttled Parallax Effect
const initParallax = () => {
    const parallaxElements = document.querySelectorAll(".parallax");
    if (!parallaxElements.length) return;

    let ticking = false;

    const updateParallax = () => {
        const scrolled = window.pageYOffset;
        parallaxElements.forEach((element) => {
            const speed = parseFloat(element.dataset.speed) || 0.5;
            element.style.transform = `translateY(${-(scrolled * speed)}px)`;
        });
        ticking = false;
    };

    window.addEventListener(
        "scroll",
        () => {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        },
        { passive: true }
    );
};

// Initialize on DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}

function init() {
    initProgressiveImages();
    initHeroSlider();
    initScrollAnimations();
    initStaggeredAnimations();
    initParallax();
}
