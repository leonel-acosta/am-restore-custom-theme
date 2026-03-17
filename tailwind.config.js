/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './**/*.php',
    './assets/js/**/*.js',
  ],
  safelist: [
    // Footer dynamic columns (md breakpoint, 12-col grid)
    'md:w-1/12', 'md:w-2/12', 'md:w-3/12', 'md:w-4/12',
    'md:w-5/12', 'md:w-6/12', 'md:w-7/12', 'md:w-8/12',
    'md:w-9/12', 'md:w-10/12', 'md:w-11/12', 'md:w-full',
    // Counter section dynamic lg widths
    'lg:w-1/6', 'lg:w-1/4', 'lg:w-1/3', 'lg:w-1/2', 'lg:w-full',
  ],
  theme: {
    container: {
      center: true,
      padding: '1rem',
    },
    extend: {
      colors: {
        primary:   '#8B1A2F',
        secondary: '#2E2E2E',
        tertiary:  '#1A1A1A',
        accent:    '#A0A0A0',
        light:     '#E8E8E8',
      },
      fontFamily: {
        sans: ['Barlow', 'Helvetica', 'Arial', 'sans-serif'],
      },
      ringColor: {
        DEFAULT: '#8B1A2F',
      },
      ringOffsetColor: {
        DEFAULT: '#ffffff',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
