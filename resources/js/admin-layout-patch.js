/**
 * Admin Layout Patch
 * Handles Idle Timeout Patch and other layout-specific scripts
 */

document.addEventListener("DOMContentLoaded", function () {
    // Patch IdleTimeoutHandler to use POST for logout
    // This is necessary because the default implementation uses GET, which causes MethodNotAllowedHttpException
    const patchIdleTimeout = setInterval(() => {
        if (window.idleTimeoutHandler) {
            clearInterval(patchIdleTimeout);

            window.idleTimeoutHandler.performLogout = function () {
                if (this.checkInterval) clearInterval(this.checkInterval);
                if (this.countdownInterval)
                    clearInterval(this.countdownInterval);

                this.showNotification(
                    "Sesi berakhir karena tidak ada aktivitas",
                    "warning",
                );

                // Create form and submit to handle POST request
                const form = document.createElement("form");
                form.method = "POST";
                form.action = window.adminLogoutUrl || "/admin/logout";
                form.setAttribute("hidden", "hidden");
                form.style.display = "none"; // This might still be blocked, but hidden is a better fallback

                const csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content");
                form.appendChild(csrf);

                document.body.appendChild(form);

                setTimeout(() => {
                    form.submit();
                }, 2000);
            };
        }
    }, 500);
});
