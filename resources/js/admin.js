/**
 * Admin Panel Application - Optimized
 * Separate bundle for admin panel with lazy loading
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

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
            this.fromStorage = false;
            this.storagePath = "";
        }
    },

    clearSelection() {
        this.previewUrl = "";
        this.fromStorage = false;
        this.storagePath = "";
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
                'meta[name="csrf-token"]'
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
                }
            );

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            this.items = data.items.filter(
                (item) => item.type === "folder" || item.isImage
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
            if (this.inputId) {
                const input = document.getElementById(this.inputId);
                if (input) input.value = "";
            }
            this.closeStorageModal();
        }
    },
}));

// Start Alpine only if not already started by Livewire
if (!window.Alpine._started) {
    window.Alpine.start();
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
    }
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
                    ?.getAttribute("content")
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
