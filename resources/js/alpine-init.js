// Alpine.js Initialization Helper
// This file ensures Alpine.js is properly initialized after components are registered

// Wait for Alpine to be available
function waitForAlpine(callback, maxAttempts = 50) {
    let attempts = 0;
    const checkInterval = setInterval(() => {
        attempts++;
        if (window.Alpine) {
            clearInterval(checkInterval);
            callback();
        } else if (attempts >= maxAttempts) {
            clearInterval(checkInterval);
            console.error(
                "[Alpine Init] Alpine.js failed to load after",
                maxAttempts,
                "attempts",
            );
        }
    }, 100);
}

// Initialize Alpine when ready
waitForAlpine(() => {
    console.log("[Alpine Init] Alpine.js is ready");
    console.log(
        "[Alpine Init] Alpine version:",
        window.Alpine.version || "3.x",
    );

    // Verify all components are registered
    const components = [
        "adminLayout",
        "fileUpload",
        "arrayItems",
        "imagePicker",
        "modal",
        "formValidation",
        "dropdown",
        "tabs",
        "permissionManager",
        "reportForm",
        "mapPicker",
        "backupManager",
        "companyInfoForm",
        "repeaterField",
    ];

    const missing = components.filter(
        (name) => typeof window[name] !== "function",
    );

    if (missing.length > 0) {
        console.warn("[Alpine Init] Missing components:", missing);
    } else {
        console.log("[Alpine Init] All components registered successfully");
    }

    // Start Alpine.js explicitly since we're using Vite bundle (no defer attribute)
    console.log("[Alpine Init] Starting Alpine.js...");
    window.Alpine.start();
    console.log("[Alpine Init] Alpine.js initialization complete");
});

// Export for manual testing
window.waitForAlpine = waitForAlpine;

// Debug helper function
window.testAlpineComponent = function (componentName) {
    if (typeof window[componentName] === "function") {
        console.log(`[Alpine Test] ${componentName} is registered`);
        return window[componentName]();
    } else {
        console.error(`[Alpine Test] ${componentName} is NOT registered`);
        return null;
    }
};
