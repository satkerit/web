/**
 * News Form Enhancement
 * Additional validation and UX improvements for news form
 */

(function ($) {
    "use strict";

    // Configuration
    const CONFIG = {
        maxFeaturedImageSize: 2 * 1024 * 1024, // 2MB
        maxGalleryImageSize: 2 * 1024 * 1024, // 2MB
        maxEditorImageSize: 5 * 1024 * 1024, // 5MB
        maxGalleryImages: 7,
        autoSaveInterval: 30000, // 30 seconds
        validImageTypes: [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/webp",
            "image/gif",
        ],
        minTitleLength: 10,
        minContentLength: 100,
        maxTitleLength: 255,
        maxExcerptLength: 500,
        maxMetaDescriptionLength: 160,
        maxTagsLength: 255,
    };

    // State management
    let formState = {
        isDirty: false,
        isSubmitting: false,
        autoSaveEnabled: true,
        lastSaved: null,
    };

    /**
     * Initialize all enhancements
     */
    function init() {
        console.log("News Form Enhancement initialized");

        // Enhanced validation
        initFormValidation();

        // Better image handling
        initImageHandling();

        // Auto-save with indicator
        initAutoSave();

        // Unsaved changes warning
        initUnsavedWarning();

        // Keyboard shortcuts
        initKeyboardShortcuts();

        // Real-time character counters
        initCharacterCounters();

        // Form state tracking
        initFormStateTracking();
    }

    /**
     * Enhanced form validation
     */
    function initFormValidation() {
        $("#news-form").on("submit", function (e) {
            if (formState.isSubmitting) {
                e.preventDefault();
                return false;
            }

            const errors = validateForm();

            if (errors.length > 0) {
                e.preventDefault();
                showValidationErrors(errors);
                return false;
            }

            // Mark as submitting
            formState.isSubmitting = true;
            formState.isDirty = false;

            // Show loading state
            const submitBtn = $("#submit-btn");
            submitBtn.addClass("loading");
            submitBtn.html('<div class="spinner"></div> Menyimpan...');
            submitBtn.prop("disabled", true);

            return true;
        });
    }

    /**
     * Validate form fields
     */
    function validateForm() {
        const errors = [];

        // Title validation
        const title = $("#title-input").val().trim();
        if (!title) {
            errors.push({
                field: "title",
                message: "Judul berita wajib diisi",
            });
        } else if (title.length < CONFIG.minTitleLength) {
            errors.push({
                field: "title",
                message: `Judul minimal ${CONFIG.minTitleLength} karakter`,
            });
        } else if (title.length > CONFIG.maxTitleLength) {
            errors.push({
                field: "title",
                message: `Judul maksimal ${CONFIG.maxTitleLength} karakter`,
            });
        }

        // Content validation
        const content = $("#summernote").summernote("code");
        const textContent = $("<div>").html(content).text().trim();
        if (!textContent || textContent === "") {
            errors.push({
                field: "content",
                message: "Konten berita wajib diisi",
            });
        } else if (textContent.length < CONFIG.minContentLength) {
            errors.push({
                field: "content",
                message: `Konten minimal ${CONFIG.minContentLength} karakter`,
            });
        }

        // Category validation
        const category = $('select[name="category"]').val();
        if (!category) {
            errors.push({
                field: "category",
                message: "Kategori wajib dipilih",
            });
        }

        // Excerpt validation
        const excerpt = $('textarea[name="excerpt"]').val().trim();
        if (excerpt && excerpt.length > CONFIG.maxExcerptLength) {
            errors.push({
                field: "excerpt",
                message: `Ringkasan maksimal ${CONFIG.maxExcerptLength} karakter`,
            });
        }

        // Meta description validation
        const metaDesc = $("#meta-description").val().trim();
        if (metaDesc && metaDesc.length > CONFIG.maxMetaDescriptionLength) {
            errors.push({
                field: "meta_description",
                message: `Meta description maksimal ${CONFIG.maxMetaDescriptionLength} karakter`,
            });
        }

        // Tags validation
        const tags = $("#tags-input").val().trim();
        if (tags && tags.length > CONFIG.maxTagsLength) {
            errors.push({
                field: "tags",
                message: `Tags maksimal ${CONFIG.maxTagsLength} karakter`,
            });
        }

        return errors;
    }

    /**
     * Show validation errors
     */
    function showValidationErrors(errors) {
        // Clear previous errors
        $(".form-error").remove();
        $(".form-input").removeClass("border-red-500");

        // Show errors
        errors.forEach((error) => {
            const field = $(
                `[name="${error.field}"], #${error.field}-input, #${error.field.replace("_", "-")}`,
            );

            if (field.length) {
                field.addClass("border-red-500");
                field.after(
                    `<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>${error.message}</div>`,
                );
            }

            showNotification(error.message, "error");
        });

        // Focus on first error
        if (errors.length > 0) {
            const firstField = $(
                `[name="${errors[0].field}"], #${errors[0].field}-input`,
            );
            if (firstField.length) {
                $("html, body").animate(
                    {
                        scrollTop: firstField.offset().top - 100,
                    },
                    500,
                );
                firstField.focus();
            }
        }
    }

    /**
     * Enhanced image handling
     */
    function initImageHandling() {
        // Featured image validation
        $("#featured_image").on("change", function () {
            const file = this.files[0];
            if (file) {
                if (
                    !validateImage(
                        file,
                        CONFIG.maxFeaturedImageSize,
                        "featured",
                    )
                ) {
                    this.value = "";
                }
            }
        });

        // Gallery images validation
        $("#slide_images").on("change", function () {
            const files = this.files;
            if (files.length > 0) {
                if (!validateGalleryImages(files)) {
                    this.value = "";
                }
            }
        });
    }

    /**
     * Validate single image
     */
    function validateImage(file, maxSize, type) {
        // Check file type
        if (!CONFIG.validImageTypes.includes(file.type)) {
            showNotification(
                "Format file tidak valid. Gunakan JPEG, PNG, atau WebP.",
                "error",
            );
            return false;
        }

        // Check file size
        if (file.size > maxSize) {
            const maxSizeMB = (maxSize / (1024 * 1024)).toFixed(1);
            showNotification(
                `Ukuran file terlalu besar. Maksimal ${maxSizeMB}MB.`,
                "error",
            );
            return false;
        }

        return true;
    }

    /**
     * Validate gallery images
     */
    function validateGalleryImages(files) {
        // Check count
        if (files.length > CONFIG.maxGalleryImages) {
            showNotification(
                `Maksimal ${CONFIG.maxGalleryImages} gambar untuk galeri.`,
                "error",
            );
            return false;
        }

        // Validate each file
        for (let i = 0; i < files.length; i++) {
            if (
                !validateImage(files[i], CONFIG.maxGalleryImageSize, "gallery")
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Auto-save with visual indicator
     */
    function initAutoSave() {
        let autoSaveTimer;
        let saveIndicator = null;

        // Create save indicator
        function createSaveIndicator() {
            if (!saveIndicator) {
                saveIndicator = $("<div>")
                    .attr("id", "auto-save-indicator")
                    .addClass(
                        "fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg text-sm z-50",
                    )
                    .hide()
                    .appendTo("body");
            }
            return saveIndicator;
        }

        // Show save status
        function showSaveStatus(status, message) {
            const indicator = createSaveIndicator();
            const colors = {
                saving: "bg-blue-500 text-white",
                saved: "bg-green-500 text-white",
                error: "bg-red-500 text-white",
            };

            indicator
                .removeClass("bg-blue-500 bg-green-500 bg-red-500")
                .addClass(colors[status])
                .html(message)
                .fadeIn(200);

            if (status === "saved" || status === "error") {
                setTimeout(() => {
                    indicator.fadeOut(200);
                }, 2000);
            }
        }

        // Auto-save function
        function performAutoSave() {
            if (!formState.autoSaveEnabled || !formState.isDirty) {
                return;
            }

            showSaveStatus("saving", "💾 Menyimpan draft...");

            try {
                const formData = {
                    title: $("#title-input").val(),
                    slug: $("#slug-input").val(),
                    content: $("#summernote").summernote("code"),
                    excerpt: $('textarea[name="excerpt"]').val(),
                    category: $('select[name="category"]').val(),
                    author: $('input[name="author"]').val(),
                    meta_description: $("#meta-description").val(),
                    tags: $("#tags-input").val(),
                    published_at: $('input[name="published_at"]').val(),
                    is_published: $('input[name="is_published"]').is(
                        ":checked",
                    ),
                    timestamp: new Date().toISOString(),
                };

                // Save to localStorage
                const draftKey = "news_draft_" + (window.newsId || "new");
                localStorage.setItem(draftKey, JSON.stringify(formData));

                formState.lastSaved = new Date();
                showSaveStatus("saved", "✓ Draft tersimpan");

                console.log(
                    "Auto-save completed at",
                    formState.lastSaved.toLocaleTimeString(),
                );
            } catch (error) {
                console.error("Auto-save error:", error);
                showSaveStatus("error", "✗ Gagal menyimpan");
            }
        }

        // Trigger auto-save on changes
        $("input, textarea, select").on("change input", function () {
            formState.isDirty = true;

            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(
                performAutoSave,
                CONFIG.autoSaveInterval,
            );
        });

        // Manual save shortcut (Ctrl+S)
        $(document).on("keydown", function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === "s") {
                e.preventDefault();
                performAutoSave();
            }
        });
    }

    /**
     * Warn about unsaved changes
     */
    function initUnsavedWarning() {
        $(window).on("beforeunload", function (e) {
            if (formState.isDirty && !formState.isSubmitting) {
                const message =
                    "Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?";
                e.returnValue = message;
                return message;
            }
        });
    }

    /**
     * Keyboard shortcuts
     */
    function initKeyboardShortcuts() {
        $(document).on("keydown", function (e) {
            // Ctrl+S: Save draft
            if ((e.ctrlKey || e.metaKey) && e.key === "s") {
                e.preventDefault();
                // Trigger auto-save
                return false;
            }

            // Ctrl+Enter: Submit form
            if ((e.ctrlKey || e.metaKey) && e.key === "Enter") {
                e.preventDefault();
                $("#news-form").submit();
                return false;
            }

            // Esc: Cancel/go back
            if (e.key === "Escape") {
                if (confirm("Batalkan dan kembali ke daftar berita?")) {
                    window.location.href = $("#news-form")
                        .find("a.btn-secondary")
                        .attr("href");
                }
                return false;
            }
        });

        // Show shortcuts hint
        const shortcutsHint = $("<div>")
            .addClass(
                "fixed bottom-4 left-4 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg shadow-lg opacity-75",
            )
            .html(
                "💡 <strong>Shortcuts:</strong> Ctrl+S (Save) | Ctrl+Enter (Submit) | Esc (Cancel)",
            )
            .appendTo("body")
            .hide();

        // Show on hover
        $(document).on("keydown", function (e) {
            if (e.ctrlKey || e.metaKey) {
                shortcutsHint.fadeIn(200);
                setTimeout(() => shortcutsHint.fadeOut(200), 3000);
            }
        });
    }

    /**
     * Real-time character counters
     */
    function initCharacterCounters() {
        // Title counter
        $("#title-input").on("input", function () {
            const length = $(this).val().length;
            const max = CONFIG.maxTitleLength;
            updateCounter($(this), length, max);
        });

        // Excerpt counter
        $('textarea[name="excerpt"]').on("input", function () {
            const length = $(this).val().length;
            const max = CONFIG.maxExcerptLength;
            updateCounter($(this), length, max);
        });

        // Tags counter
        $("#tags-input").on("input", function () {
            const length = $(this).val().length;
            const max = CONFIG.maxTagsLength;
            updateCounter($(this), length, max);
        });
    }

    /**
     * Update character counter
     */
    function updateCounter(element, length, max) {
        let counter = element.siblings(".char-counter");

        if (counter.length === 0) {
            counter = $('<div class="char-counter"></div>');
            element.parent().css("position", "relative");
            element.after(counter);
        }

        counter.text(`${length}/${max}`);
        counter.removeClass("warning danger");

        if (length > max) {
            counter.addClass("danger");
        } else if (length > max * 0.9) {
            counter.addClass("warning");
        }
    }

    /**
     * Form state tracking
     */
    function initFormStateTracking() {
        // Track changes
        $("input, textarea, select").on("change input", function () {
            formState.isDirty = true;
        });

        // Reset on submit
        $("#news-form").on("submit", function () {
            formState.isDirty = false;
            formState.isSubmitting = true;
        });
    }

    // Initialize when document is ready
    $(document).ready(function () {
        // Only initialize if news form exists
        if ($("#news-form").length > 0) {
            init();
        }
    });
})(jQuery);
