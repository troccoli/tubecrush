const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        "./resources/**/*.blade.php",
        './public/**/*.html',
        "./resources/**/*.{js, jsx, ts, tsx, vue}",
    ],

    theme: {
        fontFamily: {
            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
        },
        animation: {
            'pulse-bg-once': 'pulse-bg-once 3s ease-in forwards',
            'reverse-spin': 'reverse-spin 1s linear infinite'
        },
        keyframes: {
            'pulse-bg-once': {
                to: {backgroundColor: 'transparent'}
            },
            'reverse-spin': {
                from: {transform: 'rotate(360deg)'},
            }
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            gray: colors.gray,
            red: colors.red,
            yellow: colors.yellow,
            green: colors.green,
            blue: colors.blue,
            pink: colors.pink,
            indigo: colors.indigo,
            twitter: '#55acee',
            facebook: '#3b5998',
        }
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ]
};
