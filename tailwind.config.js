/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',

        './Modules/*/resources/views/**/*.blade.php',
        './Modules/*/resources/assets/**/*.js',
        './Modules/*/resources/assets/**/*.css',

    ],
    theme: {
        extend: {
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
