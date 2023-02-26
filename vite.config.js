import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/select2.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
