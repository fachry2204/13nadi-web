import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/main.ts'],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          img: [],
        },
      },
    }),
    tailwindcss(),
  ],
})
