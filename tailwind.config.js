/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
        fontFamily: {
            'montserrat': ['Montserrat', 'sans-serif']
        },
        width: {
            '128': '32rem'
        }
    },
  },
  plugins: [
      require('flowbite/plugin')
  ],
}
