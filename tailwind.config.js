module.exports = {
  content: [
      './node_modules/alurkerja-ui/dist/*.{js,ts,jsx,tsx}',
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.jsx",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')({
      strategy: 'class',
    }),
    require('tailwind-scrollbar')({ nocompatible: true }),
  ],
}

