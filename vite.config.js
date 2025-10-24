import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  server: {
    host: '0.0.0.0',
    cors: true,
    origin: 'http://bscn.test',              // origem que o browser vai ver
    hmr: { host: 'bscn.test', protocol: 'http' } // corrige o [::1]:5173
  },
  plugins: [
    laravel({
      input: ['resources/css/app.scss','resources/js/app.js'],
      refresh: true,
    }),
  ],
})
