const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./themes/tailwind/**/*.{blade.php,js,vue,ts}",
        "node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx,vue}",
        "node_modules/flowbite/**/*.{js,jsx,ts,tsx}"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            opacity: ["disabled"],
        },
    },

    plugins: [require("@tailwindcss/forms"),require('flowbite/plugin')],
};
