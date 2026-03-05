/**
 * Alpine.js Bundle - Local Installation
 * This file bundles Alpine.js locally to avoid CDN tracking prevention issues
 */

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

// Register Alpine plugins
Alpine.plugin(collapse);

// Make Alpine available globally
window.Alpine = Alpine;

// Log Alpine initialization
console.log("[Alpine Bundle] Alpine.js loaded from local bundle");
console.log("[Alpine Bundle] Version:", Alpine.version || "3.x");

// Export Alpine for module usage
export default Alpine;
