// Admin Panel Application - Optimized
// Note: Alpine components are now registered in alpine-components.js
// This file handles jQuery plugins, SweetAlert, and other admin functionality

// Global error handler for browser extension errors
window.addEventListener("unhandledrejection", function (event) {
    // Suppress errors from browser extensions
    if (
        (event.reason &&
            event.reason.message &&
            event.reason.message.includes("message channel closed")) ||
        (event.reason.message &&
            event.reason.message.includes(
                "Listener indicated an asynchronous response",
            ))
    ) {
        event.preventDefault();
        return;
    }
});

import "./bootstrap";

// Import Alpine.js bundle first to ensure it's available
import "./alpine-bundle";

// jQuery & Summernote (Required for WYSIWYG Editor)
import $ from "jquery";
window.jQuery = window.$ = $;

import "summernote/dist/summernote-lite.css";
import "summernote/dist/summernote-lite.js";

// SweetAlert 2
import Swal from "sweetalert2";
window.Swal = Swal;

// Initialize Summernote and other jQuery scripts
$(document).ready(function () {
    // Summernote Initialization
    if ($("#summernote").length > 0) {
        try {
            $("#summernote").summernote({
                placeholder: "Tulis konten berita di sini...",
                tabsize: 2,
                height: 400,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]],
                ],
                callbacks: {
                    onInit: function () {
                        console.log("Summernote initialized successfully");
                    },
                    onChange: function (contents, $editable) {
                        $("#summernote").val(contents);
                    },
                },
            });
        } catch (e) {
            console.error("Summernote initialization error:", e);
        }
    }

    // Slug Generator for News Form
    if ($("#title").length > 0 && $("#slug").length > 0) {
        $("#title").on("input", function () {
            var title = $(this).val();
            var slug = title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-")
                .trim();
            $("#slug").val(slug);
        });
    }

    // Image Preview
    $(".image-preview").on("change", ".custom-file-input", function () {
        var file = this.files[0];
        var $preview = $(this).closest(".image-preview").find(".preview");
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $preview.attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Delete confirmation
    $(document).on("click", ".btn-delete", function (e) {
        e.preventDefault();
        var form = $(this).closest("form");
        var title = $(this).data("title") || "Apakah Anda yakin?";
        var text =
            $(this).data("text") ||
            "Data yang dihapus tidak dapat dikembalikan!";
        var confirmText = $(this).data("confirm") || "Ya, hapus!";
        var cancelText = $(this).data("cancel") || "Batal";

        Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // DataTable default options
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
            },
            responsive: true,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"],
            ],
            pageLength: 10,
        });
    }

    // Initialize DataTable if exists
    if ($(".datatable").length > 0) {
        $(".datatable").DataTable();
    }

    // Tooltip initialization
    if ($('[data-bs-toggle="tooltip"]').length > 0) {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    // Popover initialization
    if ($('[data-bs-toggle="popover"]').length > 0) {
        $('[data-bs-toggle="popover"]').popover();
    }

    // Sidebar toggle for mobile
    $(".sidebar-toggle").on("click", function (e) {
        e.preventDefault();
        $("body").toggleClass("sidebar-open");
    });

    // Close sidebar when clicking outside on mobile
    $(document).on("click", ".sidebar-overlay", function () {
        $("body").removeClass("sidebar-open");
    });

    // Auto-hide alerts
    $(".alert-auto-hide").each(function () {
        var $alert = $(this);
        var delay = $alert.data("delay") || 5000;
        setTimeout(function () {
            $alert.fadeOut("slow", function () {
                $(this).remove();
            });
        }, delay);
    });

    // Confirm form submission
    $(".btn-confirm").on("click", function (e) {
        e.preventDefault();
        var form = $(this).closest("form");
        var title = $(this).data("title") || "Konfirmasi";
        var text = $(this).data("text") || "Apakah Anda yakin?";

        Swal.fire({
            title: title,
            text: text,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Status toggle
    $(".btn-status").on("click", function (e) {
        e.preventDefault();
        var btn = $(this);
        var form = btn.closest("form");
        var title = btn.data("title") || "Ubah Status";
        var text =
            btn.data("text") || "Apakah Anda yakin ingin mengubah status?";

        Swal.fire({
            title: title,
            text: text,
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Bulk actions
    $(".btn-bulk").on("click", function (e) {
        e.preventDefault();
        var btn = $(this);
        var form = btn.closest("form");
        var action = btn.data("action");
        var title = btn.data("title") || "Konfirmasi";
        var text = btn.data("text") || "Apakah Anda yakin?";

        // Check if any checkbox is selected
        if ($(".bulk-checkbox:checked").length === 0) {
            Swal.fire({
                title: "Peringatan",
                text: "Pilih minimal satu data",
                icon: "warning",
                confirmButtonColor: "#198754",
            });
            return;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                form.attr("action", action);
                form.submit();
            }
        });
    });

    // Select all checkboxes
    $(".select-all").on("change", function () {
        var isChecked = $(this).is(":checked");
        $(this)
            .closest("table")
            .find(".bulk-checkbox")
            .prop("checked", isChecked);
    });

    // Checkbox selection counter
    $(".bulk-checkbox").on("change", function () {
        var count = $(".bulk-checkbox:checked").length;
        $(".selected-count").text(count + " data dipilih");
    });

    // Image gallery lightbox
    $(".lightbox").on("click", function (e) {
        e.preventDefault();
        var src = $(this).attr("href") || $(this).attr("src");
        Swal.fire({
            imageUrl: src,
            imageAlt: "Gambar",
            showConfirmButton: false,
            showCloseButton: true,
            width: "90%",
        });
    });

    // Character counter for textarea
    $(".char-counter").on("input", function () {
        var max = $(this).data("max") || 500;
        var current = $(this).val().length;
        var $counter = $(this).siblings(".counter");
        $counter.text(current + "/" + max);
    });

    // Toggle password visibility
    $(".toggle-password").on("click", function () {
        var input = $($(this).attr("toggle"));
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    // Initialize password toggles
    $('[toggle="#password"]').each(function () {
        $(this).on("click", function () {
            var input = $($(this).attr("toggle"));
            var icon = $(this).find("i");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });

    // Date picker
    if ($.fn.datepicker) {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            language: "id",
        });
    }

    // Time picker
    if ($.fn.timepicker) {
        $(".timepicker").timepicker({
            showMeridian: false,
            minuteStep: 5,
        });
    }

    // DateTime picker
    if ($.fn.datetimepicker) {
        $(".datetimepicker").datetimepicker({
            format: "yyyy-mm-dd hh:ii:ss",
            autoclose: true,
            todayHighlight: true,
            language: "id",
        });
    }

    // Color picker
    if ($.fn.colorpicker) {
        $(".colorpicker").colorpicker({
            format: "hex",
        });
    }

    // Input mask
    if ($.fn.inputmask) {
        $(".date-mask").inputmask("yyyy-mm-dd");
        $(".phone-mask").inputmask("+62 999-9999-9999");
        $(".currency-mask").inputmask({
            alias: "currency",
            prefix: "Rp ",
            digits: 0,
            groupSeparator: ".",
            radixPoint: ",",
            autoGroup: true,
            removeMaskOnSubmit: true,
        });
    }

    // AJAX form submission
    $(".ajax-form").on("submit", function (e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();

        submitBtn
            .prop("disabled", true)
            .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: "Berhasil",
                        text: response.message || "Data berhasil disimpan",
                        icon: "success",
                        confirmButtonColor: "#198754",
                    }).then(() => {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Gagal",
                        text: response.message || "Terjadi kesalahan",
                        icon: "error",
                        confirmButtonColor: "#dc3545",
                    });
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON?.errors;
                var message = "Terjadi kesalahan";

                if (errors) {
                    message = Object.values(errors)[0][0];
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: "Gagal",
                    text: message,
                    icon: "error",
                    confirmButtonColor: "#dc3545",
                });
            },
            complete: function () {
                submitBtn.prop("disabled", false).html(originalText);
            },
        });
    });

    // AJAX link click
    $(".ajax-link").on("click", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        var title = $(this).data("title") || "Konfirmasi";
        var text = $(this).data("text") || "Apakah Anda yakin?";

        Swal.fire({
            title: title,
            text: text,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    var popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]'),
    );
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Lazy load SweetAlert only when needed
let swalLoaded = false;
let swalPromise = null;

async function loadSwal() {
    if (swalLoaded) {
        return window.Swal;
    }

    if (swalPromise) {
        return swalPromise;
    }

    swalPromise = new Promise((resolve, reject) => {
        const nonce = document
            .querySelector('meta[name="csp-nonce"]')
            ?.getAttribute("content");
        const script = document.createElement("script");
        script.src =
            "https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js";
        if (nonce) script.setAttribute("nonce", nonce);
        script.onload = () => {
            swalLoaded = true;
            resolve(window.Swal);
        };
        script.onerror = reject;
        document.head.appendChild(script);
    });

    return swalPromise;
}

// Expose loadSwal globally
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
