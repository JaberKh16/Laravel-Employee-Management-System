/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js",
  ],
  darkMode: 'class', // or 'media' — required for Flowbite dark mode components
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554',
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('flowbite/plugin'),
    // Uncomment if you're using Tailwind typography
    // require('@tailwindcss/typography'),
    // Uncomment if you're using forms
    // require('@tailwindcss/forms'),
    // Uncomment if you're using aspect-ratio (pre v3.0)
    // require('@tailwindcss/aspect-ratio'),
  ],
}