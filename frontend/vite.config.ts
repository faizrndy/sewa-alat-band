import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    vue(),
  ],

  resolve: {
    alias: {
      // Ini cara paling standar di Vite agar '@' mengarah ke folder 'src'
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },

  server: {
    // Penting! Pakai 127.0.0.1 supaya cocok persis dengan whitelist CORS Laravel
    host: '127.0.0.1', 
    port: 3000,
    open: true, // Otomatis buka browser saat 'npm run dev'
  },
})