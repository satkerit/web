/**
 * News Form JavaScript
 * Handles all form interactions for create and update news/articles
 */

(function ($) {
    "use strict";

    // Configuration
    const CONFIG = {
        autoSaveInterval: 30000, // 30 seconds
        maxImageSize: 5 * 1024 * 1024, // 5MB
        maxGalleryImages: 7,
        summernoteHeight: 800,
        summernoteMinHeight: 600,
        summernoteMaxHeight: 1500,
    };

    // State management
    const state = {
        autoSaveTimer: null,
        isInitialized: false,
        isDirty: false,
    };

    /**
     * Initialize the form
     */
    function init() {
        if (state.isInitialized) {
            console.warn("Form already initialized");
            return;
        }

        console.log("Initializing news form...");

        initSummernote();
        initEventListeners();
        initCharacterCounters();
        initAutoSave();
        loadDraftIfAvailable();
        updateProgress();
        updateWordCount();

        state.isInitialized = true;
        console.log("News form initialized successfully");
    }

    /**
     * Initialize Summernote editor
     */
    function initSummernote() {
        const $summernote = $("#summernote");

        if (!$summernote.length) {
            console.error("Summernote element not found");
            return;
        }

        // Get initial content from textarea value (already unescaped by browser)
        const initialContent = $summernote.val() || "";
        console.log("Initial content length:", initialContent.length);

        $summernote.summernote({
            placeholder: "Tulis konten berita di sini...",
            tabsize: 2,
            height: CONFIG.summernoteHeight,
            minHeight: CONFIG.summernoteMinHeight,
            maxHeight: CONFIG.summernoteMaxHeight,
            focus: false,
            width: "100%",
            disableResizeEditor: false,
            toolbar: [
                ["style", ["style"]],
                [
                    "font",
                    [
                        "bold",
                        "italic",
                        "underline",
                        "strikethrough",
                        "superscript",
                        "subscript",
                        "clear",
                    ],
                ],
                ["fontname", ["fontname"]],
                ["fontsize", ["fontsize"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph", "height"]],
                ["table", ["table"]],
                ["insert", ["link", "picture", "video", "hr"]],
                ["view", ["fullscreen", "codeview", "help"]],
            ],
            styleTags: [
                "p",
                { title: "Heading 2", tag: "h2", className: "", value: "h2" },
                { title: "Heading 3", tag: "h3", className: "", value: "h3" },
                { title: "Heading 4", tag: "h4", className: "", value: "h4" },
                { title: "Heading 5", tag: "h5", className: "", value: "h5" },
                {
                    title: "Blockquote",
                    tag: "blockquote",
                    className: "",
                    value: "blockquote",
                },
            ],
            fontNames: [
                "Arial",
                "Arial Black",
                "Comic Sans MS",
                "Courier New",
                "Georgia",
                "Helvetica",
                "Impact",
                "Tahoma",
                "Times New Roman",
                "Verdana",
            ],
            fontNamesIgnoreCheck: [
                "Arial",
                "Arial Black",
                "Comic Sans MS",
                "Courier New",
                "Georgia",
                "Helvetica",
                "Impact",
                "Tahoma",
                "Times New Roman",
                "Verdana",
            ],
            fontSizes: [
                "8",
                "9",
                "10",
                "11",
                "12",
                "14",
                "16",
                "18",
                "20",
                "24",
                "28",
                "32",
                "36",
                "48",
                "64",
            ],
            lineHeights: [
                "0.2",
                "0.3",
                "0.4",
                "0.5",
                "0.6",
                "0.8",
                "1.0",
                "1.2",
                "1.4",
                "1.5",
                "2.0",
                "3.0",
            ],
            dialogsInBody: true,
            disableDragAndDrop: false,
            callbacks: {
                onInit: function () {
                    console.log("Summernote initialized");
                    // Set initial content if available
                    if (initialContent) {
                        $summernote.summernote("code", initialContent);
                        console.log("Initial content loaded");
                    }
                },
                onChange: function (contents, $editable) {
                    state.isDirty = true;
                    updateProgress();
                    updateWordCount();
                    triggerAutoSave();
                },
                onImageUpload: function (files) {
                    handleImageUpload(files);
                },
                onPaste: function (e) {
                    handlePaste(e);
                },
            },
        });

        console.log("Summernote configured");
    }

    /**
     * Initialize event listeners
     */
    function initEventListeners() {
        // Title to slug generation
        $("#title-input").on("input", function () {
            const title = $(this).val();
            const slug = generateSlug(title);
            $("#slug-input").val(slug);
            state.isDirty = true;
            updateProgress();
            triggerAutoSave();
        });

        // Meta description counter
        $("#meta-description").on("input", function () {
            updateMetaCounter();
            state.isDirty = true;
            updateProgress();
            triggerAutoSave();
        });

        // Form submission
        $("#news-form").on("submit", function (e) {
            handleFormSubmit(e);
        });

        // Track changes for auto-save
        $("input, select, textarea")
            .not("#summernote")
            .on("change input", function () {
                state.isDirty = true;
                updateProgress();
                triggerAutoSave();
            });

        // Featured image preview
        $("#featured_image").on("change", function () {
            previewFeaturedImage(this);
        });

        // Gallery images preview
        $("#slide_images").on("change", function () {
            previewSlideImages(this);
        });

        // Prevent accidental navigation
        $(window).on("beforeunload", function (e) {
            if (state.isDirty) {
                const message =
                    "Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?";
                e.returnValue = message;
                return message;
            }
        });

        console.log("Event listeners initialized");
    }

    /**
     * Initialize character counters
     */
    function initCharacterCounters() {
        updateMetaCounter();
    }

    /**
     * Update meta description character counter
     */
    function updateMetaCounter() {
        const $metaDesc = $("#meta-description");
        const $counter = $("#meta-counter");

        if (!$metaDesc.length || !$counter.length) return;

        const length = $metaDesc.val().length;
        $counter.text(length + "/160");

        $counter.removeClass("warning danger");
        if (length > 160) {
            $counter.addClass("danger");
        } else if (length > 140) {
            $counter.addClass("warning");
        }
    }

    /**
     * Update form progress bar
     */
    function updateProgress() {
        let progress = 0;
        const fields = [
            { selector: "#title-input", weight: 25 },
            { selector: 'select[name="category"]', weight: 25 },
            { selector: "#summernote", weight: 25 },
            { selector: 'input[name="featured_image"]', weight: 25 },
        ];

        fields.forEach((field) => {
            const $field = $(field.selector);
            let value = "";

            if (field.selector === "#summernote") {
                value = $field.summernote("code");
            } else {
                value = $field.val();
            }

            if (value && value.trim() !== "" && value !== "<p><br></p>") {
                progress += field.weight;
            }
        });

        $("#form-progress").css("width", progress + "%");
    }

    /**
     * Update word count
     */
    function updateWordCount() {
        const content = $("#summernote").summernote("code");
        const text = $("<div>").html(content).text();
        const words = text
            .trim()
            .split(/\s+/)
            .filter((word) => word.length > 0);
        const wordCount = words.length;
        $("#word-count").text(wordCount + " kata");
    }

    /**
     * Generate slug from text
     */
    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, "")
            .replace(/[\s_-]+/g, "-")
            .replace(/^-+|-+$/g, "");
    }

    /**
     * Handle image upload to editor
     */
    function handleImageUpload(files) {
        for (let i = 0; i < files.length; i++) {
            uploadImageToServer(files[i]);
        }
    }

    /**
     * Handle paste event for images
     */
    function handlePaste(e) {
        const clipboardData = e.originalEvent.clipboardData;
        if (clipboardData && clipboardData.items) {
            const items = clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") !== -1) {
                    e.preventDefault();
                    const file = items[i].getAsFile();
                    uploadImageToServer(file);
                }
            }
        }
    }

    /**
     * Upload image to server
     */
    function uploadImageToServer(file) {
        // Validate file
        if (!file.type.match("image.*")) {
            showNotification("File harus berupa gambar!", "error");
            return;
        }

        if (file.size > CONFIG.maxImageSize) {
            showNotification("Ukuran file maksimal 5MB!", "error");
            return;
        }

        const data = new FormData();
        data.append("image", file);
        data.append(
            "_token",
            $('meta[name="csrf-token"]').attr("content") ||
                $('input[name="_token"]').val(),
        );

        // Show loading indicator
        const loadingHtml =
            '<div class="text-center py-4 image-upload-loading"><div class="spinner inline-block"></div><p class="mt-2 text-sm text-gray-600">Mengupload gambar...</p></div>';
        $("#summernote").summernote("pasteHTML", loadingHtml);

        $.ajax({
            url:
                $("#news-form").data("upload-url") ||
                "/admin/storage/upload-editor-image",
            method: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                // Remove loading indicator
                $(".image-upload-loading").remove();

                if (response.success && response.url) {
                    // Insert image into editor
                    $("#summernote").summernote(
                        "insertImage",
                        response.url,
                        function ($image) {
                            $image.css("max-width", "100%");
                            $image.css("height", "auto");
                            $image.addClass("img-fluid");
                            $image.attr(
                                "alt",
                                response.filename || "Uploaded image",
                            );
                        },
                    );

                    showNotification("Gambar berhasil diupload", "success");
                } else {
                    showNotification(
                        "Gagal mengupload gambar: " +
                            (response.message || "Unknown error"),
                        "error",
                    );
                }
            },
            error: function (xhr) {
                // Remove loading indicator
                $(".image-upload-loading").remove();

                console.error("Upload error:", xhr);
                let errorMsg = "Gagal mengupload gambar.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 413) {
                    errorMsg = "Ukuran file terlalu besar. Maksimal 5MB.";
                } else if (xhr.status === 422) {
                    errorMsg =
                        "Format file tidak valid. Gunakan JPG, PNG, GIF, atau WebP.";
                }

                showNotification(errorMsg, "error");
            },
        });
    }

    /**
     * Preview featured image
     */
    function previewFeaturedImage(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];

        // Validate file type
        if (!file.type.match("image.*")) {
            showNotification("File harus berupa gambar!", "error");
            input.value = "";
            return;
        }

        // Validate file size
        if (file.size > CONFIG.maxImageSize) {
            showNotification("Ukuran file maksimal 5MB!", "error");
            input.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const $uploadArea = $(input)
                .closest(".form-group")
                .find(".image-upload-area");
            if ($uploadArea.length) {
                $uploadArea.addClass("has-image");
                $uploadArea.html(
                    '<div class="image-preview">' +
                        '<img src="' +
                        e.target.result +
                        '" alt="Featured Image Preview" id="featured-preview">' +
                        '<div class="image-overlay">' +
                        '<button type="button" class="btn btn-secondary" onclick="document.getElementById(\'featured_image\').click()">' +
                        '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>' +
                        "</svg>" +
                        "Ganti Gambar" +
                        "</button>" +
                        "</div>" +
                        "</div>",
                );
                showNotification("Preview gambar berhasil dimuat", "success");
            }
        };
        reader.onerror = function () {
            showNotification("Gagal membaca file!", "error");
        };
        reader.readAsDataURL(file);
    }

    /**
     * Preview slide/gallery images
     */
    function previewSlideImages(input) {
        console.log("previewSlideImages called");
        console.log("Files selected:", input.files ? input.files.length : 0);

        if (!input.files || input.files.length === 0) {
            console.log("No files selected");
            return;
        }

        const fileCount = input.files.length;
        console.log("Processing", fileCount, "files");

        // Get existing images count
        const existingCount = $("#existing-gallery .gallery-item").length || 0;
        console.log("Existing images:", existingCount);

        const maxImages = CONFIG.maxGalleryImages;
        const remainingSlots = maxImages - existingCount;
        console.log("Remaining slots:", remainingSlots);

        // Validate file count
        if (fileCount > remainingSlots) {
            showNotification(
                "Maksimal " +
                    remainingSlots +
                    " gambar lagi! (Total max: " +
                    maxImages +
                    ")",
                "error",
            );
            input.value = "";
            return;
        }

        // Validate each file
        let validFiles = 0;
        let invalidFiles = [];

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            console.log("File", i, ":", file.name, file.type, file.size);

            if (!file.type.match("image.*")) {
                invalidFiles.push(file.name + " (bukan gambar)");
            } else if (file.size > CONFIG.maxImageSize) {
                invalidFiles.push(
                    file.name +
                        " (terlalu besar: " +
                        Math.round((file.size / 1024 / 1024) * 10) / 10 +
                        "MB)",
                );
            } else {
                validFiles++;
            }
        }

        console.log(
            "Valid files:",
            validFiles,
            "Invalid files:",
            invalidFiles.length,
        );

        if (invalidFiles.length > 0) {
            showNotification(
                "File tidak valid: " + invalidFiles.join(", "),
                "error",
            );
            input.value = "";
            return;
        }

        // Show preview
        const $previewContainer = $("#gallery-preview");
        $previewContainer.empty().show();

        let loadedCount = 0;

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                loadedCount++;
                console.log("Preview loaded for file", loadedCount);

                const $previewItem = $("<div>")
                    .addClass("gallery-item")
                    .css("position", "relative")
                    .html(
                        '<img src="' +
                            e.target.result +
                            '" alt="Preview">' +
                            '<div style="position: absolute; top: 0.5rem; left: 0.5rem; background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">Baru</div>',
                    );
                $previewContainer.append($previewItem);
            };

            reader.onerror = function () {
                console.error("Error reading file:", file.name);
            };

            reader.readAsDataURL(file);
        }

        showNotification(fileCount + " gambar siap diupload", "success");
    }

    /**
     * Handle form submission
     */
    function handleFormSubmit(e) {
        const $submitBtn = $("#submit-btn");

        // Disable button and show loading
        $submitBtn.addClass("loading");
        $submitBtn.html('<div class="spinner"></div> Menyimpan...');
        $submitBtn.prop("disabled", true);

        // Clear dirty flag
        state.isDirty = false;

        // Clear draft
        clearDraft();

        // Form will submit normally
        return true;
    }

    /**
     * Auto-save functionality
     */
    function initAutoSave() {
        console.log("Auto-save initialized");
    }

    function triggerAutoSave() {
        clearTimeout(state.autoSaveTimer);
        state.autoSaveTimer = setTimeout(function () {
            saveDraft();
        }, CONFIG.autoSaveInterval);
    }

    function saveDraft() {
        if (!state.isDirty) return;

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
            is_published: $('input[name="is_published"]').is(":checked"),
            timestamp: new Date().toISOString(),
        };

        const draftKey = getDraftKey();
        try {
            localStorage.setItem(draftKey, JSON.stringify(formData));
            console.log("Draft saved at " + new Date().toLocaleTimeString());
        } catch (e) {
            console.error("Error saving draft:", e);
        }
    }

    function loadDraftIfAvailable() {
        // Only load draft for new posts
        const isEditMode = $("#news-form").data("edit-mode") === true;
        if (isEditMode) {
            console.log("Edit mode detected, skipping draft load");
            return;
        }

        const draftKey = getDraftKey();
        const draft = localStorage.getItem(draftKey);

        if (!draft) {
            console.log("No draft found");
            return;
        }

        if (!confirm("Ditemukan draft yang tersimpan. Muat draft?")) {
            return;
        }

        try {
            const data = JSON.parse(draft);

            $("#title-input")
                .val(data.title || "")
                .trigger("input");
            $("#slug-input").val(data.slug || "");
            $("#summernote").summernote("code", data.content || "");
            $('textarea[name="excerpt"]').val(data.excerpt || "");
            $('select[name="category"]').val(data.category || "");
            $('input[name="author"]').val(data.author || "");
            $("#meta-description").val(data.meta_description || "");
            $("#tags-input").val(data.tags || "");
            $('input[name="published_at"]').val(data.published_at || "");
            $('input[name="is_published"]').prop(
                "checked",
                data.is_published || false,
            );

            updateProgress();
            updateWordCount();
            updateMetaCounter();

            showNotification("Draft berhasil dimuat", "success");
        } catch (e) {
            console.error("Error loading draft:", e);
            showNotification("Gagal memuat draft", "error");
        }
    }

    function clearDraft() {
        const draftKey = getDraftKey();
        localStorage.removeItem(draftKey);
        console.log("Draft cleared");
    }

    function getDraftKey() {
        const newsId = $("#news-form").data("news-id") || "new";
        return "news_draft_" + newsId;
    }

    /**
     * Show notification
     */
    function showNotification(message, type) {
        type = type || "info";

        const colors = {
            success: "bg-green-500",
            error: "bg-red-500",
            warning: "bg-yellow-500",
            info: "bg-blue-500",
        };

        const bgColor = colors[type] || colors.info;

        const $notification = $("<div>")
            .addClass(
                "fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white z-50",
            )
            .addClass(bgColor)
            .text(message)
            .appendTo("body");

        setTimeout(function () {
            $notification.fadeOut(300, function () {
                $(this).remove();
            });
        }, 3000);
    }

    // Expose functions to global scope for inline event handlers
    window.previewFeaturedImage = previewFeaturedImage;
    window.previewSlideImages = previewSlideImages;

    // Initialize when document is ready
    $(document).ready(function () {
        init();
    });
})(jQuery);
