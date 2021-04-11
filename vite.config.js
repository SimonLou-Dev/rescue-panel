import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [reactRefresh()],
    root: './resources',
    base: '/assets/',
    define: {
        'process.env': {}
    },
    build: {
      outDir: '../public/assets',
        assetsDir: '',
        manifest: true,
        minify: true,
        rollupOptions: {
          output: {
              manualChunks: undefined
          },
          input:{
              'app.jsx': './resources/js/app.jsx'
          }
        }
    }
})

