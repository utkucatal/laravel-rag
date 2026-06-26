import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    server: {
        host: '0.0.0.0',                 // bind all interfaces (host can reach)
        port: 5173,
        strictPort: true,
        cors: true,                      // allow :8080 page to load :5173 assets
        origin: 'http://localhost:5173', // advertise localhost, not [::] -> fixes CORS/null errors
        hmr: { host: 'localhost' },      // HMR websocket connects back to localhost
    },
});
