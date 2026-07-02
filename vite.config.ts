import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import inertia from '@inertiajs/vite';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            // Ubah app.js menjadi app.ts di sini
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        vue(),
        inertia(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
                '**/.cursor/**', // Opsional: abaikan tracking IDE jika pakai AI tools
                '**/.claudia/**',
            ],
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
});
