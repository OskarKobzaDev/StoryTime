import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Użycie localhost zamiast IPv6
        port: 5174,        // Domyślny port
        cors: {
            origin: 'http://storytime.localhost', // Specyfikacja dozwolonego pochodzenia
            methods: ['GET', 'POST', 'PUT', 'DELETE'],
            credentials: true,
        },
        hmr: {
            host: 'localhost',
            port: 5174,
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
