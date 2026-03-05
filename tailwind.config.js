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
            pattern:
                /(bg|text)-(primary|emerald|blue|amber|rose|purple|teal|cyan|indigo)-(100|500|600)/,
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
                    50: "#f0fdfa",
                    100: "#ccfbf1",
                    200: "#99f6e4",
                    300: "#5eead4",
                    400: "#2dd4bf",
                    500: "#14b8a6",
                    600: "#0d9488",
                    700: "#0f766e",
                    800: "#115e59",
                    900: "#134e4a",
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
