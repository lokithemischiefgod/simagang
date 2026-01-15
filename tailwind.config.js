import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',

    './resources/js/**/*.js',
    './resources/js/**/*.ts',
    './resources/js/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        lintasarta: {
          navy: '#0B2C4D',
          blue: '#005BAC',
          red: '#E11D48',
          soft: '#F8FAFC',
          grey: '#a0a0a0'
        }
      }
    },
  },
  plugins: [forms],
};
