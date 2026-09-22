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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                brand: {
                    50: '#EAF2F0',
                    100: '#CFE2DD',
                    400: '#2F7A6B',
                    500: '#1B4B43',
                    600: '#153B35',
                    700: '#102B27',
                },
                accent: {
                    400: '#F0A24C',
                    500: '#E8871E',
                    600: '#C96F13',
                },
                slate: {
                    50: '#F7F8F7',
                    100: '#EEF0EE',
                    200: '#DDE1DE',
                    400: '#8C948E',
                    500: '#5C6660',
                    600: '#454E48',
                    700: '#2E3530',
                    800: '#1C211D',
                },
                danger: {
                    50: '#FDEEEC',
                    400: '#E0705C',
                    500: '#C4472F',
                    600: '#A33823',
                },
                canvas: '#F7F7F5',
            },
        },
    },

    plugins: [forms],
};