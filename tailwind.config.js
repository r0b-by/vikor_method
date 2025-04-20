/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class', // ✅ ini penting untuk dark mode dengan toggle class
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
