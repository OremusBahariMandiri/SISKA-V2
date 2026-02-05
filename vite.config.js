import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/layout.css',
                'resources/js/app.js',
                'resources/js/region-api.js',
            ],
            refresh: true,
        }),
    ],
});
