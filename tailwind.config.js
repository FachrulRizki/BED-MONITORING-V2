import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    safelist: [
        'bg-primary',
        'bg-success',
        'bg-error',
        'bg-warning',
        'text-foreground',
    ],

    theme: {
        extend: {
            colors: {
                primary: 'rgb(var(--color-primary) / <alpha-value>)',
                'primary-hover': 'rgb(var(--color-primary-hover) / <alpha-value>)',
                foreground: 'rgb(var(--color-foreground) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary) / <alpha-value>)',
                muted: 'rgb(var(--color-muted) / <alpha-value>)',
                border: 'rgb(var(--color-border) / <alpha-value>)',
                'card-grey': 'rgb(var(--color-card-grey) / <alpha-value>)',
                success: 'rgb(var(--color-success) / <alpha-value>)',
                error: 'rgb(var(--color-error) / <alpha-value>)',
                warning: 'rgb(var(--color-warning) / <alpha-value>)',
            },
            fontFamily: {
                sans: ['"Lexend Deca"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
