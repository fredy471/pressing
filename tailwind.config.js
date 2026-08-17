import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {

                text: 'var(--color-text)',
                muted: 'var(--color-muted)',
                inverse: 'var(--color-inverse)',
                accent: 'var(--color-accent)',
                accent2: 'var(--color-accent2)',
                danger: 'var(--color-danger)',
                bg: 'var(--color-bg)',
                content: 'var(--color-content)',
            },
        },
        fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
            display: ['Montserrat', 'sans-serif'],
            body: ['Inter', 'sans-serif'],
            mono: ['"Space Mono"', 'monospace'],
        },
    },

    plugins: [forms],
};