const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
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
            }
        },
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        backgroundColor: ['hover', 'disabled'],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ]
};
