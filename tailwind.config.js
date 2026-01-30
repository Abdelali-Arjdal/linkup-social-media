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
                sans: ['Inter', 'system-ui', '-apple-system', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // LinkUp Brand Colors
                primary: {
                    DEFAULT: '#007BFF',
                    50: '#E6F2FF',
                    100: '#CCE5FF',
                    200: '#99CBFF',
                    300: '#66B1FF',
                    400: '#3397FF',
                    500: '#007BFF',
                    600: '#0062CC',
                    700: '#004A99',
                    800: '#003166',
                    900: '#001933',
                },
                secondary: {
                    DEFAULT: '#FF6B6B',
                    50: '#FFF0F0',
                    100: '#FFE1E1',
                    200: '#FFC3C3',
                    300: '#FFA5A5',
                    400: '#FF8787',
                    500: '#FF6B6B',
                    600: '#CC5656',
                    700: '#994040',
                    800: '#662B2B',
                    900: '#331515',
                },
                accent: {
                    DEFAULT: '#00C897',
                    50: '#E6FCF7',
                    100: '#CCF9EF',
                    200: '#99F3DF',
                    300: '#66EDCF',
                    400: '#33E7BF',
                    500: '#00C897',
                    600: '#00A079',
                    700: '#00785B',
                    800: '#00503D',
                    900: '#00281E',
                },
                dark: {
                    DEFAULT: '#0A192F',
                    50: '#E6EBF5',
                    100: '#CCD7EB',
                    200: '#99AFD7',
                    300: '#6687C3',
                    400: '#335FAF',
                    500: '#0A192F',
                    600: '#081426',
                    700: '#060F1C',
                    800: '#040A13',
                    900: '#020509',
                },
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
        },
    },

    plugins: [forms],
};
