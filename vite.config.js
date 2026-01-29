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
                "resources/js/pagination-fix.js",
                "resources/js/quill-editor.js",
                "resources/js/idle-timeout.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimize chunk splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor chunks - split large libraries
                    "vendor-alpine": ["alpinejs", "@alpinejs/collapse"],
                    "vendor-swiper": ["swiper"],
                    "vendor-sweetalert": ["sweetalert2"],
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
            },
        },
        // CSS optimization
        cssMinify: true,
        cssCodeSplit: true,
        // Reduce chunk size warnings threshold
        chunkSizeWarningLimit: 500,
        // Source maps for production debugging (optional)
        sourcemap: false,
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ["alpinejs", "@alpinejs/collapse"],
        exclude: ["quill"], // Lazy load quill
    },
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
        },
    },
});
