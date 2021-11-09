import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'
const env = 'production';
const Global = "var process = { env: {NODE_ENV: '" + env + "'}}"


//production //development
// https://vitejs.dev/config/
export default defineConfig({
  plugins: [reactRefresh()],
    root: './resources',
    base: '/assets/',
    mode: env,
    define: {
        'process.env': {
            'NODE_ENV': env
        }
    },
    build: {
      outDir: '../public/assets',
        assetsDir: '',
        manifest: true,
        minify: true,
        rollupOptions: {
          output: {
              manualChunks: undefined,
              banner: Global
          },
          input:{
              'app.jsx': './resources/js/app.jsx'
          },

        }
    }
})

