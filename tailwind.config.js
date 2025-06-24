/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
      extend: {
            fontFamily: {
        'sans': ['Circular', 'Helvetica', 'Arial', 'sans-serif']
      },
      colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      gray: colors.slate,
      green: colors.emerald,
      purple: colors.violet,
      yellow: colors.amber,
      pink: colors.fuchsia,
      "custom-gray":"#414166",
      "lighter-gray":"#7D7D95",
      "custom-green":"#3AC273",
      "dark-green":"#3E7B57",
    },
    },
  },
  plugins: [],
}
