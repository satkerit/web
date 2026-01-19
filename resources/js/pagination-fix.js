// Pagination Fix for Frontend Pages
document.addEventListener("DOMContentLoaded", function () {
    // Fix pagination links
    const paginationLinks = document.querySelectorAll(".pagination a");

    paginationLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            // Add loading state
            const button = e.target.closest("a");
            if (button) {
                button.style.opacity = "0.6";
                button.style.pointerEvents = "none";

                // Add spinner
                const originalText = button.innerHTML;
                button.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                // Restore after delay if page doesn't change
                setTimeout(() => {
                    button.style.opacity = "1";
                    button.style.pointerEvents = "auto";
                    button.innerHTML = originalText;
                }, 3000);
            }
        });
    });

    // Fix search forms
    const searchForms = document.querySelectorAll('form[method="GET"]');

    searchForms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.style.opacity = "0.6";

                const originalText = submitButton.innerHTML;
                submitButton.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mencari...';

                // Restore after delay if form doesn't submit
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.style.opacity = "1";
                    submitButton.innerHTML = originalText;
                }, 5000);
            }
        });
    });

    // Fix card hover effects
    const cards = document.querySelectorAll(
        '.group, .card-hover, [class*="hover:"]',
    );

    cards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-4px)";
            this.style.transition = "all 0.3s ease";
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
        });
    });

    // Fix dropdown menus
    const dropdowns = document.querySelectorAll('[x-data*="open"]');

    dropdowns.forEach((dropdown) => {
        const trigger = dropdown.querySelector("[x-on\\:click], [@click]");
        const menu = dropdown.querySelector("[x-show]");

        if (trigger && menu) {
            trigger.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Toggle menu visibility
                const isVisible = menu.style.display !== "none";
                menu.style.display = isVisible ? "none" : "block";

                // Close on outside click
                document.addEventListener("click", function closeMenu(e) {
                    if (!dropdown.contains(e.target)) {
                        menu.style.display = "none";
                        document.removeEventListener("click", closeMenu);
                    }
                });
            });
        }
    });

    // Fix mobile navigation
    const mobileMenuToggle = document.querySelector(
        '[x-on\\:click="mobileOpen = !mobileOpen"], [@click="mobileOpen = !mobileOpen"]',
    );
    const mobileMenu = document.querySelector('[x-show="mobileOpen"]');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener("click", function (e) {
            e.preventDefault();
            const isVisible = mobileMenu.style.display !== "none";
            mobileMenu.style.display = isVisible ? "none" : "block";

            // Add backdrop
            if (!isVisible) {
                const backdrop = document.createElement("div");
                backdrop.className =
                    "fixed inset-0 bg-black bg-opacity-50 z-40";
                backdrop.addEventListener("click", function () {
                    mobileMenu.style.display = "none";
                    backdrop.remove();
                });
                document.body.appendChild(backdrop);
            }
        });
    }

    // Fix image loading
    const images = document.querySelectorAll('img[loading="lazy"]');

    images.forEach((img) => {
        img.addEventListener("load", function () {
            this.classList.add("loaded");
            this.classList.remove("img-loading");
        });

        img.addEventListener("error", function () {
            this.classList.add("img-error");
            this.src =
                "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQgNEgyMFYyMEg0VjRaIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIvPgo8cGF0aCBkPSJNOSA5SDE1VjE1SDlWOVoiIGZpbGw9IiNGM0Y0RjYiLz4KPC9zdmc+";
        });
    });

    // Fix scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll(
        ".fade-in-section, .slide-in-left, .slide-in-right, .scale-in, [x-intersect]",
    );

    animatedElements.forEach((el) => {
        observer.observe(el);
    });
});

// Fix for Alpine.js compatibility
document.addEventListener("alpine:init", () => {
    console.log("Alpine.js initialized - pagination fixes applied");
});

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
    module.exports = {};
}
