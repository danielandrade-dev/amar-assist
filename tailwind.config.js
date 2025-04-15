const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#621587',
                secondary: '#F36606',
                text: '#61CE70',
                success: '#61CE70',
                danger: '#F36606',
                warning: '#F36606',
                info: '#17a2b8',
                light: '#f8f9fa',
                dark: '#343a40',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
