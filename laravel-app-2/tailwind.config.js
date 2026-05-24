import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                headline: ["Noto Serif", "serif"],
                body: ["Manrope", "sans-serif"],
                label: ["Manrope", "sans-serif"],
            },
            colors: {
                "primary":                  "#361f1a",
                "primary-container":        "#4e342e",
                "on-primary":               "#ffffff",
                "secondary":                "#705d00",
                "secondary-container":      "#fcd400",
                "on-secondary-container":   "#6e5c00",
                "secondary-fixed-dim":      "#e9c400",
                "surface":                  "#faf9f6",
                "surface-container-lowest": "#ffffff",
                "surface-container-low":    "#f4f3f1",
                "surface-container":        "#efeeeb",
                "surface-container-high":   "#e9e8e5",
                "surface-container-highest":"#e3e2e0",
                "on-surface":               "#1a1c1a",
                "on-surface-variant":       "#504442",
                "outline":                  "#827471",
                "outline-variant":          "#d4c3bf",
                "primary-fixed":            "#ffdad2",
                "on-primary-fixed":         "#2b1611",
            },
            fontSize: {
                'golden-h1': ['42px', { lineHeight: '55px' }],
                'golden-h2': ['26px', { lineHeight: '42px' }],
                'golden-body': ['16px', { lineHeight: '26px' }],
                'golden-caption': ['10px', { lineHeight: '16px' }],
            },
        },
    },

    plugins: [forms],
};
