import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],

  server: {
    host: "127.0.0.1",   // FE berjalan di 127.0.0.1
    port: 3000,
    open: true,          // otomatis buka browser
  },

  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },

  build: {
    target: 'esnext',
    outDir: 'dist',
  },
})
