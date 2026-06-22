import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/admin.css",
                "resources/css/frontend-fixes.css",
                "resources/js/app.js",
                "resources/js/admin.js",
                "resources/js/alpine-bundle.js",
                "resources/js/alpine-components.js",
                "resources/js/alpine-init.js",
                "resources/js/idle-timeout.js",
                "resources/js/admin-layout-patch.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimize chunk splitting
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes("node_modules")) {
                        if (id.includes("alpinejs")) {
                            return "vendor-alpine";
                        }
                        if (id.includes("swiper")) {
                            return "vendor-swiper";
                        }
                        if (id.includes("sweetalert")) {
                            return "vendor-sweetalert";
                        }
                        return "vendor"; // General vendor chunk
                    }
                },
                // Optimize asset file names
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split(".");
                    const ext = info[info.length - 1];
                    if (/\.(css)$/.test(assetInfo.name)) {
                        return `assets/css/[name]-[hash][extname]`;
                    }
                    return `assets/[name]-[hash][extname]`;
                },
                chunkFileNames: "assets/js/[name]-[hash].js",
                entryFileNames: "assets/js/[name]-[hash].js",
            },
        },
        // Minification settings
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                passes: 2, // More aggressive compression
            },
            format: {
                comments: false, // Remove all comments
            },
        },
        // CSS optimization
        cssMinify: "lightningcss", // Faster CSS minification
        cssCodeSplit: true,
        // Reduce chunk size warnings threshold
        chunkSizeWarningLimit: 500,
        // Source maps for production debugging (optional)
        sourcemap: false,
        // Target modern browsers
        target: "es2020",
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ["alpinejs", "@alpinejs/collapse"],
        exclude: [],
        esbuildOptions: {
            target: "es2020",
        },
    },
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
        },
    },
});
