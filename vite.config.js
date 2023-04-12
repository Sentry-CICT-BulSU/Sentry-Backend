import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd(), '') };
    return defineConfig({
        server: {
            hmr: {
                host: process.env.VITE_APP_URL_FLAT,
            },
        },
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
        ],
    });

}
