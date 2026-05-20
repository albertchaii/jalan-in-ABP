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
            },
            colors: {
                maroon: {
                    50: '#fdf2f4',
                    100: '#fce7eb',
                    200: '#f9d0d9',
                    300: '#f4a9ba',
                    400: '#ec7a95',
                    500: '#df4d72',
                    600: '#cc2d5a',
                    700: '#ab2049',
                    800: '#8B2E3B',
                    900: '#6B1D2A',
                    950: '#3d0c17',
                },
            },
        },
    },

    plugins: [forms],
};
