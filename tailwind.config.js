const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    prefix: 'sqmss-',
    content: [
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            aspectRatio: {
                'squad-flag': '512 / 269',
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};