/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    colors:{
      transparent: 'transparent',
      current: 'currentColor',
      'brun_terreux': '#7B6C3E',
      'beige_chaud': '#D4C5A5',
      'gris_ardoise': '#8F8F8F',
      'brun_terreux_clair': '#CFC4A0',
      'beige_chaud_clair': '#EFE9DC',
      'gris_ardoise_clair': '#E6E6E6',
    },
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

