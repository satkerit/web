// Alpine.js Components - Must be loaded BEFORE Alpine.js initializes
// This file registers all Alpine components globally

console.log("[Alpine] Loading components...");

// ============================================
// 1. ADMIN LAYOUT COMPONENT
// ============================================
window.adminLayout = function () {
    return {
        sidebarOpen: false,
        isMobile: false,
        searchOpen: false,

        init() {
            this.checkMobile();
            window.addEventListener("resize", () => this.checkMobile(), {
                passive: true,
            });
            console.log("[Alpine] adminLayout initialized");
        },

        checkMobile() {
            this.isMobile = window.innerWidth < 1024;
            if (!this.isMobile) {
                this.sidebarOpen = false;
            }
        },

        openSidebar() {
            this.sidebarOpen = true;
        },

        closeSidebar() {
            this.sidebarOpen = false;
        },

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
    };
};

// ============================================
// 2. FILE UPLOAD COMPONENT
// ============================================
window.fileUpload = function (name, currentFile = null, maxSize = 2048) {
    return {
        hasFile: false,
        hasError: false,
        isImage: false,
        fileName: "",
        fileSize: "",
        previewUrl: "",
        errorMessage: "",
        currentFileUrl: currentFile || "",
        currentFileName: currentFile ? currentFile.split("/").pop() : "",
        shouldDelete: "0",

        init() {
            const input = document.getElementById(name);
            if (input) {
                input.addEventListener("change", (e) =>
                    this.handleFileSelect(e),
                );
            }
            console.log("[Alpine] fileUpload initialized for", name);
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) {
                this.resetFile();
                return;
            }

            // Validate file size
            if (file.size > maxSize * 1024) {
                this.errorMessage = `File terlalu besar. Maksimal ${maxSize}KB`;
                this.hasError = true;
                this.resetFile();
                return;
            }

            this.hasFile = true;
            this.hasError = false;
            this.errorMessage = "";
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
            this.shouldDelete = "0";

            // Check if image for preview
            if (file.type.startsWith("image/")) {
                this.isImage = true;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.isImage = false;
            }
        },

        removeFile() {
            this.resetFile();
            const input = document.getElementById(name);
            if (input) input.value = "";
        },

        removeCurrentFile() {
            this.shouldDelete = "1";
            this.currentFileUrl = "";
            this.currentFileName = "";
        },

        resetFile() {
            this.hasFile = false;
            this.hasError = false;
            this.isImage = false;
            this.fileName = "";
            this.fileSize = "";
            this.previewUrl = "";
            this.errorMessage = "";
        },

        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (
                parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
            );
        },
    };
};

// ============================================
// 3. IMAGE PICKER COMPONENT
// ============================================
window.imagePicker = function (config = {}) {
    return {
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

        init() {
            console.log("[Alpine] imagePicker initialized:", {
                previewUrl: this.previewUrl,
            });
        },

        openModal() {
            this.showModal = true;
            this.loadDirectory("");
        },

        closeModal() {
            this.showModal = false;
        },

        async loadDirectory(path) {
            this.loading = true;
            try {
                const response = await fetch(
                    `/admin/storage/browse?path=${encodeURIComponent(path)}`,
                );
                const data = await response.json();
                this.items = data.items || [];
                this.breadcrumbs = data.breadcrumbs || [];
                this.currentPath = path;
            } catch (error) {
                console.error("[Alpine] Error loading directory:", error);
            } finally {
                this.loading = false;
            }
        },

        selectItem(item) {
            if (item.type === "file") {
                this.previewUrl = item.url;
                this.fromStorage = true;
                this.storagePath = item.path;
                this.showModal = false;
                this.updateInput();
            } else if (item.type === "dir") {
                this.loadDirectory(item.path);
            }
        },

        updateInput() {
            const input = document.getElementById(this.inputId);
            if (input) {
                input.value = this.storagePath;
            }
        },

        removeImage() {
            this.previewUrl = "";
            this.fromStorage = false;
            this.storagePath = "";
            this.shouldDelete = true;
            this.updateInput();
        },
    };
};

// ============================================
// 4. ARRAY ITEMS COMPONENT (for education, experience, etc)
// ============================================
window.arrayItems = function (initialItems = []) {
    return {
        items: initialItems && initialItems.length > 0 ? initialItems : [""],

        init() {
            console.log("[Alpine] arrayItems initialized:", this.items);
        },

        addItem() {
            this.items.push("");
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        updateItem(index, value) {
            this.items[index] = value;
        },
    };
};

// ============================================
// 5. MODAL COMPONENT
// ============================================
window.modal = function (initialOpen = false) {
    return {
        open: initialOpen,

        init() {
            if (this.open) {
                document.body.style.overflow = "hidden";
            }
            console.log("[Alpine] modal initialized");
        },

        openModal() {
            this.open = true;
            document.body.style.overflow = "hidden";
        },

        closeModal() {
            this.open = false;
            document.body.style.overflow = "";
        },

        toggleModal() {
            this.open ? this.closeModal() : this.openModal();
        },
    };
};

// ============================================
// 6. FORM VALIDATION COMPONENT
// ============================================
window.formValidation = function () {
    return {
        errors: {},
        touched: {},

        init() {
            console.log("[Alpine] formValidation initialized");
        },

        setError(field, message) {
            this.errors[field] = message;
        },

        clearError(field) {
            delete this.errors[field];
        },

        hasError(field) {
            return !!this.errors[field];
        },

        getError(field) {
            return this.errors[field] || "";
        },

        markTouched(field) {
            this.touched[field] = true;
        },

        isTouched(field) {
            return !!this.touched[field];
        },
    };
};

// ============================================
// 7. DROPDOWN COMPONENT
// ============================================
window.dropdown = function () {
    return {
        open: false,

        init() {
            document.addEventListener("click", (e) => {
                if (!this.$el.contains(e.target)) {
                    this.open = false;
                }
            });
            console.log("[Alpine] dropdown initialized");
        },

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },
    };
};

// ============================================
// 8. TABS COMPONENT
// ============================================
window.tabs = function (initialTab = 0) {
    return {
        activeTab: initialTab,

        init() {
            console.log(
                "[Alpine] tabs initialized with active tab:",
                this.activeTab,
            );
        },

        setTab(index) {
            this.activeTab = index;
        },

        isActive(index) {
            return this.activeTab === index;
        },
    };
};

window.permissionManager = function () {
    return {
        version: 0,

        bump() {
            this.version += 1;
        },

        getGroupCheckboxes(group) {
            return Array.from(
                this.$el.querySelectorAll(
                    `.permission-checkbox[data-group="${group}"]`,
                ),
            );
        },

        selectAll() {
            const boxes = this.$el.querySelectorAll(".permission-checkbox");
            boxes.forEach((box) => {
                box.checked = true;
            });
            this.bump();
        },

        deselectAll() {
            const boxes = this.$el.querySelectorAll(".permission-checkbox");
            boxes.forEach((box) => {
                box.checked = false;
            });
            this.bump();
        },

        toggleGroup(group) {
            const boxes = this.getGroupCheckboxes(group);
            const shouldCheck = !this.isGroupChecked(group);
            boxes.forEach((box) => {
                box.checked = shouldCheck;
            });
            this.bump();
        },

        updateGroupState() {
            this.bump();
        },

        isGroupChecked(group) {
            this.version;
            const boxes = this.getGroupCheckboxes(group);
            return boxes.length > 0 && boxes.every((box) => box.checked);
        },

        isGroupIndeterminate(group) {
            this.version;
            const boxes = this.getGroupCheckboxes(group);
            const checked = boxes.filter((box) => box.checked).length;
            return checked > 0 && checked < boxes.length;
        },

        getGroupCount(group) {
            this.version;
            const boxes = this.getGroupCheckboxes(group);
            const checked = boxes.filter((box) => box.checked).length;
            return `${checked}/${boxes.length}`;
        },
    };
};
window.reportForm = function (initialPostingMode = "auto") {
    return {
        postingMode: initialPostingMode || "auto",
    };
};

window.mapPicker = function (initialLatitude = "", initialLongitude = "") {
    console.log("[Alpine] mapPicker init:", {
        initialLatitude,
        initialLongitude,
    });
    return {
        mapLat: initialLatitude || "",
        mapLng: initialLongitude || "",

        get hasCoordinates() {
            const latNum = parseFloat(this.mapLat);
            const lngNum = parseFloat(this.mapLng);
            return !Number.isNaN(latNum) && !Number.isNaN(lngNum);
        },

        get mapUrl() {
            if (!this.hasCoordinates) {
                return "";
            }
            return `https://www.google.com/maps?q=${encodeURIComponent(
                `${this.mapLat},${this.mapLng}`,
            )}&z=15&output=embed`;
        },

        get directionsUrl() {
            if (!this.hasCoordinates) {
                return "https://www.google.com/maps";
            }
            return `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(
                `${this.mapLat},${this.mapLng}`,
            )}`;
        },

        updateMap() {
            if (this.$refs?.mapFrame && this.hasCoordinates) {
                this.$refs.mapFrame.src = this.mapUrl;
            }
        },
    };
};

window.backupManager = function (config = {}) {
    return {
        showCreateModal: false,
        showCleanupModal: false,
        showModal: false,
        isCreating: false,
        isCleaning: false,
        backupForm: {
            backup_type: config.backup_type || "full",
            compression: config.compression ?? true,
            description: "",
        },
        cleanupForm: {
            days: config.cleanup_days || 30,
        },
        createUrl: config.createUrl || "",
        cleanupUrl: config.cleanupUrl || "",
        restoreUrlTemplate: config.restoreUrlTemplate || "",
        deleteUrlTemplate: config.deleteUrlTemplate || "",

        get csrfToken() {
            return document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");
        },

        async notify(type, title, message) {
            if (window.Swal) {
                await window.Swal.fire({
                    icon: type,
                    title,
                    text: message,
                    confirmButtonText: "OK",
                });
                return;
            }
            if (type === "success") {
                alert(`${title} ${message}`);
                return;
            }
            alert(`${title} ${message}`);
        },

        async confirmAction(title, message) {
            if (window.Swal) {
                const result = await window.Swal.fire({
                    title,
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal",
                });
                return result.isConfirmed;
            }
            return confirm(message);
        },

        async createBackup() {
            if (!this.createUrl) {
                return;
            }
            this.isCreating = true;
            try {
                const response = await fetch(this.createUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify(this.backupForm),
                });
                const data = await response.json();
                if (data.success) {
                    await this.notify("success", "Berhasil!", data.message);
                    window.location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await this.notify(
                    "error",
                    "Error!",
                    error.message || "Terjadi kesalahan saat membuat backup.",
                );
            } finally {
                this.isCreating = false;
                this.showCreateModal = false;
                this.showModal = false;
            }
        },

        async createQuickBackup() {
            if (!this.createUrl) {
                return;
            }
            const confirmed = await this.confirmAction(
                "Buat Quick Backup?",
                "Backup full database dengan kompresi akan dibuat.",
            );
            if (!confirmed) {
                return;
            }
            this.isCreating = true;
            try {
                const response = await fetch(this.createUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify({
                        backup_type: "full",
                        compression: true,
                        description: "Quick backup",
                    }),
                });
                const data = await response.json();
                if (data.success) {
                    await this.notify("success", "Berhasil!", data.message);
                    window.location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await this.notify(
                    "error",
                    "Error!",
                    error.message || "Terjadi kesalahan saat membuat backup.",
                );
            } finally {
                this.isCreating = false;
            }
        },

        async cleanupBackups() {
            if (!this.cleanupUrl) {
                return;
            }
            this.isCleaning = true;
            try {
                const response = await fetch(this.cleanupUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify(this.cleanupForm),
                });
                const data = await response.json();
                if (data.success) {
                    await this.notify("success", "Berhasil!", data.message);
                    window.location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await this.notify(
                    "error",
                    "Error!",
                    error.message ||
                        "Terjadi kesalahan saat membersihkan backup.",
                );
            } finally {
                this.isCleaning = false;
                this.showCleanupModal = false;
            }
        },

        async confirmRestore(filename) {
            const confirmed = await this.confirmAction(
                "Restore Database?",
                `Restore database dari backup: ${filename}. Semua data akan diganti.`,
            );
            if (confirmed) {
                await this.restoreBackup(filename);
            }
        },

        async restoreBackup(filename) {
            if (!this.restoreUrlTemplate) {
                return;
            }
            try {
                const url = this.restoreUrlTemplate.replace(
                    ":filename",
                    filename,
                );
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });
                const data = await response.json();
                if (data.success) {
                    await this.notify("success", "Berhasil!", data.message);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await this.notify(
                    "error",
                    "Error!",
                    error.message || "Terjadi kesalahan saat restore backup.",
                );
            }
        },

        async confirmDelete(filename) {
            const confirmed = await this.confirmAction(
                "Hapus Backup?",
                `File "${filename}" akan dihapus permanen.`,
            );
            if (confirmed) {
                await this.deleteBackup(filename);
            }
        },

        async deleteBackup(filename) {
            if (!this.deleteUrlTemplate) {
                return;
            }
            try {
                const url = this.deleteUrlTemplate.replace(
                    ":filename",
                    filename,
                );
                const response = await fetch(url, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });
                const data = await response.json();
                if (data.success) {
                    await this.notify("success", "Berhasil!", data.message);
                    window.location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await this.notify(
                    "error",
                    "Error!",
                    error.message || "Terjadi kesalahan saat menghapus backup.",
                );
            }
        },
    };
};

// ============================================
// 8B. REPEATER FIELD COMPONENT
// ============================================
const repeaterFactory = (initialData = []) => ({
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
});

window.repeaterField = repeaterFactory;

const registerRepeater = () => {
    if (window.Alpine?.data) {
        window.Alpine.data("repeaterField", repeaterFactory);
    }
};

if (window.Alpine?.data) {
    registerRepeater();
} else {
    document.addEventListener("alpine:init", registerRepeater);
}

// ============================================
// 9. COMPANY INFO FORM COMPONENT
// ============================================
window.companyInfoForm = function () {
    return {
        init() {
            console.log("[Alpine] companyInfoForm initialized");
        },
    };
};

console.log("[Alpine] All components registered successfully");
