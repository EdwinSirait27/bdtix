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
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class', // Ubah dari 'media' ke 'class'
  theme: {
    extend: {},
  },
  plugins: [],
}