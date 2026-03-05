// Alpine.js Debug Helper
// Uncomment console.log statements to enable debugging

// Check if components are registered
console.log("[Alpine Debug] Checking component registration...");
console.log("[Alpine Debug] window.adminLayout:", typeof window.adminLayout);
console.log("[Alpine Debug] window.fileUpload:", typeof window.fileUpload);
console.log("[Alpine Debug] window.arrayItems:", typeof window.arrayItems);
console.log("[Alpine Debug] window.imagePicker:", typeof window.imagePicker);
console.log("[Alpine Debug] window.modal:", typeof window.modal);
console.log(
    "[Alpine Debug] window.formValidation:",
    typeof window.formValidation,
);
console.log("[Alpine Debug] window.dropdown:", typeof window.dropdown);
console.log("[Alpine Debug] window.tabs:", typeof window.tabs);
console.log(
    "[Alpine Debug] window.companyInfoForm:",
    typeof window.companyInfoForm,
);

// Wait for Alpine to be ready
document.addEventListener("alpine:init", () => {
    console.log("[Alpine Debug] Alpine.js initialized");
});

// Monitor Alpine errors
window.addEventListener("error", (event) => {
    if (event.message.includes("Alpine")) {
        console.error("[Alpine Debug] Alpine Error:", event.message);
        console.error("[Alpine Debug] Stack:", event.error?.stack);
    }
});

// Helper function to test component
window.testAlpineComponent = function (componentName) {
    const component = window[componentName];
    if (typeof component === "function") {
        try {
            const instance = component();
            console.log(`[Alpine Debug] ${componentName} test:`, instance);
            return instance;
        } catch (error) {
            console.error(
                `[Alpine Debug] Error testing ${componentName}:`,
                error,
            );
        }
    } else {
        console.error(`[Alpine Debug] ${componentName} is not a function`);
    }
};

// Usage: testAlpineComponent('adminLayout')
