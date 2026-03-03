import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/device-index.js',
                'resources/js/chart-loader.js',
                'resources/js/qr-scanner-loader.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        sourcemap: false,
    },
});
