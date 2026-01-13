/**
 * Quill Editor - Lazy Loaded
 * Only loads when needed
 */

let QuillPromise = null;

// Lazy load Quill
const loadQuill = async () => {
    if (!QuillPromise) {
        QuillPromise = Promise.all([
            import("quill"),
            import("quill/dist/quill.snow.css"),
        ]).then(([module]) => {
            window.Quill = module.default;
            return module.default;
        });
    }
    return QuillPromise;
};

// Initialize Quill editor function
window.initQuillEditor = async function (selector, options = {}) {
    const container = document.querySelector(selector);
    if (!container) {
        console.error("Quill container not found:", selector);
        return null;
    }

    const Quill = await loadQuill();

    const defaultOptions = {
        theme: "snow",
        placeholder: "Tulis konten di sini...",
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ align: [] }],
                ["link", "blockquote"],
                ["clean"],
            ],
        },
    };

    try {
        return new Quill(container, { ...defaultOptions, ...options });
    } catch (error) {
        console.error("Error initializing Quill:", error);
        return null;
    }
};

// Auto-initialize for elements with data-quill attribute
const initQuillElements = async () => {
    const quillElements = document.querySelectorAll("[data-quill]");
    if (!quillElements.length) return;

    for (const element of quillElements) {
        const targetId = element.getAttribute("data-quill-target");
        const targetTextarea = targetId
            ? document.getElementById(targetId)
            : null;

        const quill = await window.initQuillEditor("#" + element.id);

        if (quill && targetTextarea) {
            // Set initial content
            if (targetTextarea.value) {
                quill.root.innerHTML = targetTextarea.value;
            }

            // Sync on change
            quill.on("text-change", () => {
                targetTextarea.value = quill.root.innerHTML;
            });

            // Sync on form submit
            const form = targetTextarea.closest("form");
            if (form) {
                form.addEventListener("submit", () => {
                    const content = quill.root.innerHTML;
                    targetTextarea.value =
                        content === "<p><br></p>" ? "" : content;
                });
            }
        }
    }
};

// Initialize on DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initQuillElements);
} else {
    initQuillElements();
}

export { loadQuill };
