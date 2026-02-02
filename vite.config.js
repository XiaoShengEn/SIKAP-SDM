import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
laravel({
    input: {
        app: 'resources/js/app.js',
        tv: 'resources/js/welcome.js',
    },
    refresh: true,
}),
        tailwindcss(),
    ],
});
