import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';


export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd(), '') };
    const host = process.env.VITE_APP_URL_FLAT;
    return defineConfig({
        server: {
            host,
            hmr: { host },
        },
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/css/pdf.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
        ],
    });

}
