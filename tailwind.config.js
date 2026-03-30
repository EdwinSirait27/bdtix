// /** @type {import('tailwindcss').Config} */
// export default {
//     darkMode: 'class',
//     content: [
//         "./resources/**/*.blade.php",
//         "./resources/**/*.js",
//         "./resources/**/*.vue",
//     ],
//     theme: {
//         extend: {},
//     },
//     plugins: [],
// };
// /** @type {import('tailwindcss').Config} */
// export default {
//   content: [
//     "./resources/**/*.blade.php",
//     "./resources/**/*.js",
//     "./resources/**/*.vue",
//   ],
//   darkMode: 'class', // Ubah dari 'media' ke 'class'
//   theme: {
//     extend: {},
//   },
//   plugins: [],
// }
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        dark: '#0F172A', // Tambahkan ini untuk class bg-dark
      }
    },
  },
  safelist: [
    'from-red-600',
    'to-red-800',
    'ring-red-500/30',
    'group-hover:ring-red-500/60',
    'hover:border-red-500/50',
    'group-hover:text-red-400',
    'text-red-400',
    'bg-red-500/20',
    'border-red-500/30',
  ],
  plugins: [],
}