/**
 * Frontend Application - Optimized
 * Lazy loads heavy libraries and uses efficient event handling
 */

import "./bootstrap";
import collapse from "@alpinejs/collapse";

// Suppress errors from browser extensions
window.addEventListener("unhandledrejection", (event) => {
    // Suppress Alpine transition cancellation warnings
    if (event.reason && event.reason.isFromCancelledTransition) {
        event.preventDefault();
        return;
    }
    // Suppress browser extension errors
    if (
        event.reason && 
        (
            event.reason.message && 
            event.reason.message.includes('message channel closed')
        ) ||
        event.reason.message && 
        event.reason.message.includes('Listener indicated an asynchronous response')
    ) {
        event.preventDefault();
    }
});

// Connect to Alpine instance injected by Livewire
document.addEventListener("alpine:init", () => {
    // Register plugins
    window.Alpine.plugin(collapse);

    // Register Prayer Widget Sidebar Controller
    window.Alpine.data("prayerWidgetSidebar", () => ({
        show: true,
        minimized: false,
        topPosition: 96, // Default top position (24 * 4 = 96px)

        init() {
            // Auto minimize on mobile
            if (window.innerWidth < 1024) {
                this.minimized = true;
            }

            // Calculate top position based on header height
            this.calculateTopPosition();

            // Recalculate on resize
            window.addEventListener("resize", () => {
                this.calculateTopPosition();
                if (window.innerWidth < 1024) {
                    this.minimized = true;
                } else {
                    this.minimized = false;
                }
            });

            // Recalculate on scroll (for sticky header)
            window.addEventListener("scroll", () => {
                this.calculateTopPosition();
            });
        },

        calculateTopPosition() {
            const header = document.querySelector("header");
            if (header) {
                this.topPosition = header.offsetHeight + 16; // Header height + 1rem padding
            }
        },
    }));

    // Register Prayer Time Widget
    window.Alpine.data("prayerTimeWidget", () => ({
        loading: true,
        error: null,
        location: "Jakarta, Indonesia",
        latitude: -6.2088,
        longitude: 106.8456,
        currentTime: "",
        currentDate: "",
        prayerTimes: [],
        nextPrayer: null,
        countdown: {
            hours: "00",
            minutes: "00",
            seconds: "00",
        },
        timeInterval: null,
        countdownInterval: null,
        lastDate: null,

        init() {
            this.getUserLocation();
            this.updateCurrentTime();
            this.timeInterval = setInterval(
                () => this.updateCurrentTime(),
                1000,
            );
        },

        async getUserLocation() {
            // Check if geolocation is available and allowed
            if (!navigator.geolocation) {
                console.log("Geolocation not supported");
                this.fetchPrayerTimes();
                return;
            }

            // Check permissions first
            if (navigator.permissions) {
                try {
                    const permission = await navigator.permissions.query({
                        name: "geolocation",
                    });

                    if (permission.state === "denied") {
                        console.log(
                            "Geolocation permission denied, using default location",
                        );
                        this.fetchPrayerTimes();
                        return;
                    }
                } catch (error) {
                    // Permission API not supported, continue anyway
                    console.log(
                        "Permission check not supported, continuing...",
                    );
                }
            }

            // Try to get location
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;
                    this.reverseGeocode();
                    this.fetchPrayerTimes();
                },
                (error) => {
                    console.log("Geolocation error:", error.message);
                    this.fetchPrayerTimes();
                },
                {
                    timeout: 10000,
                    maximumAge: 300000, // Cache for 5 minutes
                    enableHighAccuracy: false,
                },
            );
        },

        async reverseGeocode() {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${this.latitude}&lon=${this.longitude}&format=json`,
                );
                const data = await response.json();
                if (data.address) {
                    const city =
                        data.address.city ||
                        data.address.town ||
                        data.address.village ||
                        data.address.county;
                    const state = data.address.state;
                    this.location =
                        city && state ? `${city}, ${state}` : "Indonesia";
                }
            } catch (error) {
                console.log("Reverse geocode error:", error);
            }
        },

        async fetchPrayerTimes() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(
                    `/api/prayer-times?latitude=${this.latitude}&longitude=${this.longitude}`,
                );
                const data = await response.json();

                if (data.success && data.timings) {
                    this.processPrayerTimes(data.timings);
                    this.findNextPrayer();
                    this.startCountdown();
                } else {
                    this.error = "Gagal memuat jadwal sholat";
                }
            } catch (error) {
                this.error = "Terjadi kesalahan saat memuat data";
                console.error("Error fetching prayer times:", error);
            } finally {
                this.loading = false;
            }
        },

        processPrayerTimes(timings) {
            const prayers = [
                { name: "Subuh", key: "Fajr", icon: "🌅" },
                { name: "Dzuhur", key: "Dhuhr", icon: "☀️" },
                { name: "Ashar", key: "Asr", icon: "🌤️" },
                { name: "Maghrib", key: "Maghrib", icon: "🌆" },
                { name: "Isya", key: "Isha", icon: "🌙" },
            ];

            this.prayerTimes = prayers.map((prayer) => ({
                name: prayer.name,
                time: timings[prayer.key],
                icon: prayer.icon,
                key: prayer.key,
                isNext: false,
            }));
        },

        findNextPrayer() {
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            for (let prayer of this.prayerTimes) {
                const [hours, minutes] = prayer.time.split(":").map(Number);
                const prayerMinutes = hours * 60 + minutes;

                if (prayerMinutes > currentMinutes) {
                    prayer.isNext = true;
                    this.nextPrayer = prayer;
                    return;
                }
            }

            if (this.prayerTimes.length > 0) {
                this.prayerTimes[0].isNext = true;
                this.nextPrayer = this.prayerTimes[0];
            }
        },

        startCountdown() {
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }

            this.updateCountdown();
            this.countdownInterval = setInterval(
                () => this.updateCountdown(),
                1000,
            );
        },

        updateCountdown() {
            if (!this.nextPrayer) return;

            const now = new Date();
            const [hours, minutes] = this.nextPrayer.time
                .split(":")
                .map(Number);

            let target = new Date();
            target.setHours(hours, minutes, 0, 0);

            if (target <= now) {
                target.setDate(target.getDate() + 1);
            }

            const diff = target - now;

            if (diff <= 0) {
                this.findNextPrayer();
                return;
            }

            const totalSeconds = Math.floor(diff / 1000);
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;

            this.countdown = {
                hours: String(h).padStart(2, "0"),
                minutes: String(m).padStart(2, "0"),
                seconds: String(s).padStart(2, "0"),
            };
        },

        updateCurrentTime() {
            const now = new Date();

            this.currentTime = now.toLocaleTimeString("id-ID", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
            });

            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            this.currentDate = now.toLocaleDateString("id-ID", options);

            const currentDate = now.toDateString();
            if (this.lastDate && this.lastDate !== currentDate) {
                this.fetchPrayerTimes();
            }
            this.lastDate = currentDate;
        },

        destroy() {
            if (this.timeInterval) clearInterval(this.timeInterval);
            if (this.countdownInterval) clearInterval(this.countdownInterval);
        },
    }));

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
});

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
    },
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
                        ".slide-content",
                    );
                if (content) {
                    content.classList.remove("animate-in");
                    requestAnimationFrame(() =>
                        content.classList.add("animate-in"),
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
            { once: true },
        );

        img.addEventListener(
            "error",
            () => {
                img.classList.remove("img-loading");
                img.classList.add("img-error");
            },
            { once: true },
        );
    });
};

// Optimized Intersection Observer for scroll animations
const initScrollAnimations = () => {
    const animatedElements = document.querySelectorAll(
        ".fade-in-section, .slide-in-left, .slide-in-right, .scale-in, .stats-counter, .reveal-on-scroll",
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
        { threshold: 0.1, rootMargin: "0px 0px -50px 0px" },
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
        { passive: true },
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
