/**
 * Idle Timeout Handler
 * Menangani timeout sesi user dan memberikan peringatan sebelum logout otomatis
 */

class IdleTimeoutHandler {
    constructor(options = {}) {
        this.idleTimeout = options.idleTimeout || 30 * 60 * 1000; // 30 menit dalam ms
        this.warningTime = options.warningTime || 5 * 60 * 1000; // 5 menit dalam ms
        this.logoutUrl = options.logoutUrl || "/login";
        this.extendUrl = options.extendUrl || "/extend-session";
        this.autoExtend = options.autoExtend || true;

        this.lastActivity = Date.now();
        this.warningShown = false;
        this.countdownInterval = null;
        this.checkInterval = null;

        this.init();
    }

    init() {
        this.bindEvents();
        this.startMonitoring();
        this.createWarningModal();
    }

    bindEvents() {
        // Events yang menandakan user masih aktif
        const events = [
            "mousedown",
            "mousemove",
            "keypress",
            "scroll",
            "touchstart",
            "click",
        ];

        // Throttle function to limit how often updateActivity is called
        let activityThrottle = false;
        const throttledUpdateActivity = () => {
            if (!activityThrottle) {
                this.updateActivity();
                activityThrottle = true;
                setTimeout(() => {
                    activityThrottle = false;
                }, 200); // Only update once every 200ms
            }
        };

        events.forEach((event) => {
            document.addEventListener(
                event,
                throttledUpdateActivity,
                { passive: true, capture: true },
            );
        });

        // Handle AJAX responses untuk cek idle timeout
        if (window.axios) {
            window.axios.interceptors.response.use(
                (response) => {
                    // Check for updated activity time from backend
                    const lastActivity = response.headers["x-last-activity"];
                    const currentTime = response.headers["x-current-time"];

                    if (lastActivity && currentTime) {
                        // Sync with backend activity time (convert to milliseconds)
                        this.lastActivity = parseInt(lastActivity) * 1000;

                        // If warning is shown but session was extended, hide it
                        if (this.warningShown) {
                            const now = Date.now();
                            const idleTime = now - this.lastActivity;
                            const timeUntilLogout = this.idleTimeout - idleTime;

                            // If we have more than warning time left, hide the warning
                            if (timeUntilLogout > this.warningTime) {
                                this.hideWarning();
                            }
                        }
                    } else {
                        // Fallback to current time
                        this.updateActivity();
                    }

                    return response;
                },
                (error) => {
                    if (
                        error.response &&
                        error.response.status === 401 &&
                        error.response.data.idle_timeout
                    ) {
                        this.handleIdleLogout(error.response.data.message);
                    }
                    return Promise.reject(error);
                },
            );
        }
    }

    updateActivity() {
        this.lastActivity = Date.now();

        if (this.warningShown) {
            this.hideWarning();
        }
    }

    startMonitoring() {
        this.checkInterval = setInterval(() => {
            this.checkIdleTime();
        }, 1000); // Check setiap detik

        // Sync with backend every 30 seconds to ensure accuracy
        this.syncInterval = setInterval(() => {
            this.syncWithBackend();
        }, 30000);
    }

    checkIdleTime() {
        const now = Date.now();
        const idleTime = now - this.lastActivity;
        const timeUntilLogout = this.idleTimeout - idleTime;

        // Logout only if idle time exceeds timeout (with 10 second tolerance to match backend)
        if (idleTime >= this.idleTimeout + 10000) {
            this.performLogout();
        } else if (timeUntilLogout <= this.warningTime && !this.warningShown) {
            this.showWarning(Math.ceil(timeUntilLogout / 1000));
        }
    }

    createWarningModal() {
        const modal = document.createElement("div");
        modal.id = "idle-warning-modal";
        modal.className =
            "fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden";
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Peringatan Sesi</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Sesi Anda akan berakhir dalam <span id="countdown-timer" class="font-bold text-red-600"></span> detik karena tidak ada aktivitas.
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Klik "Perpanjang Sesi" untuk melanjutkan atau "Logout" untuk keluar sekarang.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="extend-session-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Perpanjang
                        </button>
                        <button id="logout-now-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Bind button events
        document
            .getElementById("extend-session-btn")
            .addEventListener("click", () => {
                this.extendSession();
            });

        document
            .getElementById("logout-now-btn")
            .addEventListener("click", () => {
                this.performLogout();
            });
    }

    showWarning(secondsLeft) {
        this.warningShown = true;
        const modal = document.getElementById("idle-warning-modal");
        const timer = document.getElementById("countdown-timer");

        modal.classList.remove("hidden");

        this.countdownInterval = setInterval(() => {
            // Recalculate time remaining based on current activity
            const now = Date.now();
            const idleTime = now - this.lastActivity;
            const timeUntilLogout = this.idleTimeout - idleTime;
            const actualSecondsLeft = Math.ceil(timeUntilLogout / 1000);

            // If session was extended, hide warning
            if (actualSecondsLeft > this.warningTime / 1000) {
                this.hideWarning();
                return;
            }

            // Update timer with actual remaining time
            timer.textContent = Math.max(0, actualSecondsLeft);

            // Only logout if actually timed out
            if (actualSecondsLeft <= 0) {
                clearInterval(this.countdownInterval);
                this.performLogout();
            }
        }, 1000);

        timer.textContent = Math.max(0, secondsLeft);
    }

    hideWarning() {
        this.warningShown = false;
        const modal = document.getElementById("idle-warning-modal");
        modal.classList.add("hidden");

        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
    }

    async syncWithBackend() {
        try {
            if (window.axios) {
                const response = await window.axios.get("/session/status");
                if (response.data.authenticated) {
                    // Update last activity based on backend data
                    this.lastActivity = response.data.last_activity * 1000; // Convert to milliseconds

                    // If warning is shown but backend says we have more time, hide it
                    if (
                        this.warningShown &&
                        response.data.time_remaining > this.warningTime / 1000
                    ) {
                        this.hideWarning();
                    }
                }
            }
        } catch (error) {
            // Silently handle sync errors to avoid disrupting user experience
            console.debug("Session sync failed:", error);
        }
    }

    async extendSession() {
        try {
            if (window.axios) {
                await window.axios.post(this.extendUrl);
            } else {
                // Fallback untuk extend session tanpa axios
                await fetch(this.extendUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                });
            }

            this.updateActivity();
            this.hideWarning();

            // Show success message
            this.showNotification("Sesi berhasil diperpanjang", "success");
        } catch (error) {
            console.error("Failed to extend session:", error);
            this.showNotification("Gagal memperpanjang sesi", "error");
        }
    }

    performLogout() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }

        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }

        // Show logout message
        this.showNotification(
            "Sesi berakhir karena tidak ada aktivitas",
            "warning",
        );

        // Redirect to logout
        setTimeout(() => {
            this.submitLogout();
        }, 2000);
    }

    handleIdleLogout(message) {
        this.showNotification(
            message || "Sesi berakhir karena tidak ada aktivitas",
            "warning",
        );

        setTimeout(() => {
            this.submitLogout();
        }, 2000);
    }

    submitLogout() {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = this.logoutUrl;
        form.style.display = "none";

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        if (csrfToken) {
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        document.body.appendChild(form);
        form.submit();
    }

    showNotification(message, type = "info") {
        // Create notification element
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${this.getNotificationClass(
            type,
        )}`;
        notification.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    ${this.getNotificationIcon(type)}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    getNotificationClass(type) {
        const classes = {
            success: "bg-green-50 border border-green-200 text-green-800",
            error: "bg-red-50 border border-red-200 text-red-800",
            warning: "bg-yellow-50 border border-yellow-200 text-yellow-800",
            info: "bg-blue-50 border border-blue-200 text-blue-800",
        };
        return classes[type] || classes.info;
    }

    getNotificationIcon(type) {
        const icons = {
            success:
                '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            error: '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            warning:
                '<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            info: '<svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
        };
        return icons[type] || icons.info;
    }

    destroy() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }

        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }

        if (this.syncInterval) {
            clearInterval(this.syncInterval);
        }

        const modal = document.getElementById("idle-warning-modal");
        if (modal) {
            modal.remove();
        }
    }
}

// Auto-initialize when DOM is ready (for non-admin pages)
document.addEventListener("DOMContentLoaded", function () {
    // Skip if already initialized by admin.js
    if (window.idleTimeoutHandler) {
        return;
    }

    // Get configuration from meta tags or use defaults
    const idleTimeout =
        document
            .querySelector('meta[name="idle-timeout"]')
            ?.getAttribute("content") || 30;
    const warningTime =
        document
            .querySelector('meta[name="idle-warning"]')
            ?.getAttribute("content") || 5;
    const logoutUrl =
        document
            .querySelector('meta[name="logout-url"]')
            ?.getAttribute("content") || "/login";
    const autoExtend =
        document
            .querySelector('meta[name="auto-extend"]')
            ?.getAttribute("content") === "true";

    // Only initialize if meta tags exist (user is authenticated)
    if (document.querySelector('meta[name="idle-timeout"]')) {
        // Initialize idle timeout handler
        window.idleTimeoutHandler = new IdleTimeoutHandler({
            idleTimeout: parseInt(idleTimeout) * 60 * 1000, // Convert to milliseconds
            warningTime: parseInt(warningTime) * 60 * 1000, // Convert to milliseconds
            logoutUrl: logoutUrl,
            extendUrl: "/extend-session",
            autoExtend: autoExtend,
        });
    }
});

// Export class for use in other modules
window.IdleTimeoutHandler = IdleTimeoutHandler;

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
    module.exports = IdleTimeoutHandler;
}
