import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],

    // Safelist classes that might be dynamically generated
    safelist: [
        // Animation delays - explicit classes
        "animation-delay-100",
        "animation-delay-200",
        "animation-delay-300",
        "animation-delay-400",
        "animation-delay-500",
        // Grid columns
        "grid-cols-1",
        "grid-cols-2",
        "grid-cols-3",
        "grid-cols-4",
        "grid-cols-5",
        "sm:grid-cols-2",
        "sm:grid-cols-3",
        "md:grid-cols-2",
        "md:grid-cols-3",
        "md:grid-cols-4",
        "lg:grid-cols-3",
        "lg:grid-cols-4",
        "lg:grid-cols-4",
        "lg:grid-cols-5",
        // Dynamic colors for Why Choose Us
        {
            pattern: /(bg|text)-(primary|emerald|blue|amber|rose|purple|teal|cyan|indigo)-(100|500|600)/,
            variants: ["group-hover"],
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                heading: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                },
            },
            animation: {
                float: "float 6s ease-in-out infinite",
                "pulse-glow": "pulse-glow 2s ease-in-out infinite",
            },
            keyframes: {
                float: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-10px)" },
                },
                "pulse-glow": {
                    "0%, 100%": {
                        boxShadow: "0 0 20px rgba(59, 218, 203, 0.4)",
                    },
                    "50%": { boxShadow: "0 0 40px rgba(59, 218, 203, 0.6)" },
                },
            },
        },
    },

    plugins: [forms, typography],
};
