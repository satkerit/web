/**
 * Admin Panel Application - Optimized
 * Separate bundle for admin panel with lazy loading
 */

import "./bootstrap";

// Alpine.js - Core functionality (handled by Livewire)
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

// Wait for Livewire to be ready, then register components
document.addEventListener('livewire:init', () => {
    // Only register components if Alpine exists and not started
    if (window.Alpine && !window.Alpine._started) {
        window.Alpine.plugin(collapse);
        registerAlpineComponents();
        window.Alpine._started = true;
    }
});

function registerAlpineComponents() {
    // Register adminLayout component globally
    window.Alpine.data("adminLayout", () => ({
    sidebarOpen: false,
    isMobile: false,

    init() {
        this.checkMobile();
        window.addEventListener("resize", this.handleResize.bind(this), {
            passive: true,
        });

        this.$watch("sidebarOpen", (value) => {
            if (this.isMobile) {
                document.body.style.overflow = value ? "hidden" : "";
            }
        });
    },

    checkMobile() {
        this.isMobile = window.innerWidth < 1024;
    },

    handleResize() {
        const wasMobile = this.isMobile;
        this.checkMobile();

        if (wasMobile && !this.isMobile) {
            this.sidebarOpen = false;
            document.body.style.overflow = "";
        }
    },

    openSidebar() {
        this.sidebarOpen = true;
    },

    closeSidebar() {
        this.sidebarOpen = false;
    },

    closeSidebarOnMobile() {
        if (this.isMobile) {
            this.sidebarOpen = false;
        }
    },
}));

// Register productForm component for product pages
window.Alpine.data("productForm", () => ({
    // Add any form-specific logic here
}));

// Register permissionManager component for role management pages
window.Alpine.data("permissionManager", () => ({
    init() {
        // Initialize group states on page load
        document.querySelectorAll("[data-group]").forEach((checkbox) => {
            const group = checkbox.dataset.group;
            this.updateGroupState(group);
        });
    },

    selectAll() {
        document
            .querySelectorAll(".permission-checkbox")
            .forEach((cb) => (cb.checked = true));
        this.updateAllGroupStates();
    },

    deselectAll() {
        document
            .querySelectorAll(".permission-checkbox")
            .forEach((cb) => (cb.checked = false));
        this.updateAllGroupStates();
    },

    toggleGroup(group) {
        const checkboxes = document.querySelectorAll(`[data-group="${group}"]`);
        const allChecked = Array.from(checkboxes).every((cb) => cb.checked);
        checkboxes.forEach((cb) => (cb.checked = !allChecked));
    },

    isGroupChecked(group) {
        const checkboxes = document.querySelectorAll(`[data-group="${group}"]`);
        return Array.from(checkboxes).every((cb) => cb.checked);
    },

    isGroupIndeterminate(group) {
        const checkboxes = document.querySelectorAll(`[data-group="${group}"]`);
        const checked = Array.from(checkboxes).filter(
            (cb) => cb.checked,
        ).length;
        return checked > 0 && checked < checkboxes.length;
    },

    getGroupCount(group) {
        const checkboxes = document.querySelectorAll(`[data-group="${group}"]`);
        const checked = Array.from(checkboxes).filter(
            (cb) => cb.checked,
        ).length;
        return `${checked}/${checkboxes.length}`;
    },

    updateGroupState(group) {
        // Force Alpine to re-evaluate
        this.$nextTick(() => {});
    },

    updateAllGroupStates() {
        const groups = [
            ...new Set(
                Array.from(document.querySelectorAll("[data-group]")).map(
                    (cb) => cb.dataset.group,
                ),
            ),
        ];
        groups.forEach((group) => this.updateGroupState(group));
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

// Register imagePicker component for image upload with storage browser
window.Alpine.data("imagePicker", (config = {}) => ({
    showModal: false,
    loading: false,
    currentPath: "",
    items: [],
    breadcrumbs: [],
    selectedItem: null,
    previewUrl: config.initialPreview || "",
    fromStorage: false,
    storagePath: "",
    inputId: config.inputId || "",
    hasExistingImage: config.hasExistingImage || false,
    shouldDelete: false,

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
            this.fromStorage = false;
            this.storagePath = "";
            this.shouldDelete = false; // Reset delete flag when new file is selected
        }
    },

    clearSelection() {
        this.previewUrl = "";
        this.fromStorage = false;
        this.storagePath = "";
        // Set shouldDelete to true if there was an existing image
        if (this.hasExistingImage) {
            this.shouldDelete = true;
        }
        if (this.inputId) {
            const input = document.getElementById(this.inputId);
            if (input) input.value = "";
        }
    },

    openStorageModal() {
        this.showModal = true;
        this.selectedItem = null;
        this.loadDirectory("");
    },

    closeStorageModal() {
        this.showModal = false;
    },

    async loadDirectory(path) {
        this.loading = true;
        this.currentPath = path;
        this.updateBreadcrumbs(path);

        try {
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]',
            )?.content;
            const response = await fetch(
                `/admin/storage/api/browse?path=${encodeURIComponent(path)}`,
                {
                    credentials: "same-origin",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrfToken || "",
                    },
                },
            );

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            this.items = data.items.filter(
                (item) => item.type === "folder" || item.isImage,
            );
        } catch (error) {
            console.error("Error loading directory:", error);
            this.items = [];
        }

        this.loading = false;
    },

    navigateTo(path) {
        this.selectedItem = null;
        this.loadDirectory(path);
    },

    updateBreadcrumbs(path) {
        if (!path) {
            this.breadcrumbs = [];
            return;
        }

        let currentPath = "";
        this.breadcrumbs = path.split("/").map((part) => {
            currentPath = currentPath ? `${currentPath}/${part}` : part;
            return { name: part, path: currentPath };
        });
    },

    selectImage(item) {
        if (item.type === "file" && item.isImage) {
            this.selectedItem = item;
        }
    },

    confirmSelection() {
        if (this.selectedItem) {
            this.previewUrl = this.selectedItem.url;
            this.fromStorage = true;
            this.storagePath = this.selectedItem.path;
            this.shouldDelete = false; // Reset delete flag when selecting from storage
            if (this.inputId) {
                const input = document.getElementById(this.inputId);
                if (input) input.value = "";
            }
            this.closeStorageModal();
        }
    },
}));

// Register reportForm component for report pages
window.Alpine.data("reportForm", (initialPostingMode = "auto") => ({
    postingMode: initialPostingMode,
}));

// Register auctionForm component for auction pages
window.Alpine.data("auctionForm", (initialStatus = "upcoming") => ({
    status: initialStatus,
}));

// Register mapPicker component for office location picker
window.Alpine.data("mapPicker", (initialLat = "", initialLng = "") => ({
        latitude: initialLat,
        longitude: initialLng,

        get hasCoordinates() {
            return (
                this.latitude &&
                this.longitude &&
                !isNaN(parseFloat(this.latitude)) &&
                !isNaN(parseFloat(this.longitude))
            );
        },

        get mapUrl() {
            if (!this.hasCoordinates) return "";
            const lat = parseFloat(this.latitude);
            const lng = parseFloat(this.longitude);
            return `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000!2d${lng}!3d${lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sid!2sid!4v1234567890!5m2!1sid!2sid&markers=color:red%7C${lat},${lng}`;
        },

        get directionsUrl() {
            if (!this.hasCoordinates) return "#";
            return `https://www.google.com/maps/dir/?api=1&destination=${this.latitude},${this.longitude}`;
        },

updateMap() {
            // Map updates automatically via Alpine.js reactivity
        },
    }));
}

// Lazy load SweetAlert2 only when needed
let SwalPromise = null;
const loadSwal = () => {
    if (!SwalPromise) {
        SwalPromise = import("sweetalert2").then(async (module) => {
            // Load CSS
            await import("sweetalert2/dist/sweetalert2.min.css");
            window.Swal = module.default;
            return module.default;
        });
    }
    return SwalPromise;
};

window.Swal = new Proxy(
    {},
    {
        get(_, prop) {
            return async (...args) => {
                const Swal = await loadSwal();
                return Swal[prop](...args);
            };
        },
    },
);

// Expose loadSwal for direct usage
window.loadSwal = loadSwal;

// Initialize Idle Timeout Handler for authenticated users
document.addEventListener("DOMContentLoaded", function () {
    // Check if user is authenticated (meta tags exist)
    const idleTimeoutMeta = document.querySelector('meta[name="idle-timeout"]');

    if (idleTimeoutMeta && window.IdleTimeoutHandler) {
        const idleTimeout =
            parseInt(idleTimeoutMeta.getAttribute("content")) || 30;
        const warningTime =
            parseInt(
                document
                    .querySelector('meta[name="idle-warning"]')
                    ?.getAttribute("content"),
            ) || 5;
        const logoutUrl =
            document
                .querySelector('meta[name="logout-url"]')
                ?.getAttribute("content") || "/login";
        const autoExtend =
            document
                .querySelector('meta[name="auto-extend"]')
                ?.getAttribute("content") === "true";

        // Initialize idle timeout handler
        window.idleTimeoutHandler = new window.IdleTimeoutHandler({
            idleTimeout: idleTimeout * 60 * 1000, // Convert to milliseconds
            warningTime: warningTime * 60 * 1000, // Convert to milliseconds
            logoutUrl: logoutUrl,
            extendUrl: "/extend-session",
            autoExtend: autoExtend,
        });
    }
});

// Register reportForm component for report pages
window.Alpine.data("reportForm", (initialPostingMode = "auto") => ({
    postingMode: initialPostingMode,
}));

// Register auctionForm component for auction pages
window.Alpine.data("auctionForm", (initialStatus = "upcoming") => ({
    status: initialStatus,
}));

// Register mapPicker component for office location picker
window.Alpine.data("mapPicker", (initialLat = "", initialLng = "") => ({
    latitude: initialLat,
    longitude: initialLng,

    get hasCoordinates() {
        return (
            this.latitude &&
            this.longitude &&
            !isNaN(parseFloat(this.latitude)) &&
            !isNaN(parseFloat(this.longitude))
        );
    },

    get mapUrl() {
        if (!this.hasCoordinates) return "";
        const lat = parseFloat(this.latitude);
        const lng = parseFloat(this.longitude);
        return `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000!2d${lng}!3d${lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sid!2sid!4v1234567890!5m2!1sid!2sid&markers=color:red%7C${lat},${lng}`;
    },

    get directionsUrl() {
        if (!this.hasCoordinates) return "#";
        return `https://www.google.com/maps/dir/?api=1&destination=${this.latitude},${this.longitude}`;
    },

    updateMap() {
        // Map updates automatically via Alpine.js reactivity
    },
}));
