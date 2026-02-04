import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd()); // reads VITE_* from .env
    const host = env.VITE_HOST || 'localhost';
    const port = Number(env.VITE_PORT || 5173);

    return {
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
        server: {
            host: true, // listen on 0.0.0.0 inside Docker
            port,
            strictPort: true,
            // Required when the app is served from another origin (nginx:8080).
            // Without this, browsers will block Vite module scripts (CORS).
            cors: true,
            // Important for Docker: browser must NOT be told to load assets from 0.0.0.0
            origin: `http://${host}:${port}`,
            hmr: {
                host,
                port,
            },
        },
    };
});
