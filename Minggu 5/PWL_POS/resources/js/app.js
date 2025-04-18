import './bootstrap';
import "../sass/app.scss";
import 'laravel-datatables-vite';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            external: ['fsevents'],
        },
    },
});
