/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'custom-purple': '#4A1A4A',
        'custom-green': '#10B981',
        'custom-gray': '#6B7280',
        'light-blue': '#3B82F6',
        'light-green': '#10B981',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}