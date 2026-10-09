import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/* Palet "graffiti jalanan": beton, aspal, dan cat semprot. */
const concrete = {
    50: '#f7f5f0',
    100: '#efece4',
    200: '#e0dcd1',
    300: '#cbc5b7',
    400: '#a9a294',
    500: '#8a8375',
    600: '#6e6859',
    700: '#555043',
    800: '#2e2b26',
    900: '#1c1a17',
    950: '#100f0d',
};

const asphalt = {
    50: '#f7f5f0',
    100: '#efece4',
    200: '#e0dcd1',
    300: '#cbc5b7',
    400: '#a9a294',
    500: '#8a8375',
    600: '#6e6859',
    700: '#555043',
    800: '#2e2b26',
    900: '#1c1a17',
    950: '#100f0d',
};

const spray = {
    50: '#fff0f6',
    100: '#ffe0ec',
    200: '#ffc2d9',
    300: '#ff96bd',
    400: '#ff5f99',
    500: '#ff3377',
    600: '#e00b56',
    700: '#bd0a4a',
    800: '#9c0d41',
    900: '#800f3a',
    950: '#4e021e',
};

const cyan = {
    50: '#ecfdff',
    100: '#d2f8ff',
    200: '#a9f0ff',
    300: '#71e4ff',
    400: '#29d3ff',
    500: '#00bfe6',
    600: '#0096bb',
    700: '#037793',
    800: '#0b5e74',
    900: '#0d4e61',
    950: '#023345',
};

const acid = {
    50: '#f6ffe9',
    100: '#eaffce',
    200: '#d6ffa3',
    300: '#b9f871',
    400: '#99ea45',
    500: '#7cd724',
    600: '#5cb814',
    700: '#469113',
    800: '#387116',
    900: '#2f5d17',
    950: '#173407',
};

const traffic = {
    50: '#fff9e6',
    100: '#fff0bf',
    200: '#ffe387',
    300: '#ffd247',
    400: '#ffbf1f',
    500: '#f5a300',
    600: '#d97e00',
    700: '#b45e02',
    800: '#914a0a',
    900: '#783d0d',
    950: '#451f02',
};

const tag = {
    50: '#fff1f0',
    100: '#ffe1de',
    200: '#ffc7c3',
    300: '#ffa09a',
    400: '#ff6d64',
    500: '#f9423a',
    600: '#e11d16',
    700: '#bd1610',
    800: '#9c1611',
    900: '#811713',
    950: '#470706',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                slate: concrete,
                gray: concrete,
                zinc: concrete,
                neutral: concrete,
                stone: concrete,
                indigo: spray,
                sky: cyan,
                emerald: acid,
                amber: traffic,
                rose: tag,
                red: tag,
                asphalt,
                concrete,
                spray,
                acid,
                traffic,
                tag,
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Anton', 'Impact', 'sans-serif'],
                marker: ['"Permanent Marker"', 'cursive'],
            },
            boxShadow: {
                sticker: '5px 5px 0 rgba(16, 15, 13, 0.9)',
                'sticker-sm': '3px 3px 0 rgba(16, 15, 13, 0.85)',
                'sticker-lg': '8px 8px 0 rgba(16, 15, 13, 0.9)',
            },
        },
    },

    plugins: [forms, typography],
};
