/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#FF6B35',
          dark: '#E85A2A',
          light: '#FF8555',
        },
        secondary: {
          DEFAULT: '#1A1A2E',
          light: '#2A2A3E',
        },
        accent: {
          purple: '#9D4EDD',
          blue: '#3A86FF',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
