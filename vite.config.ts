import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import inertia from '@inertiajs/vite';
import path from 'path';
// 1. Import plugin auto-import dan resolver Vant
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { VantResolver } from '@vant/auto-import-resolver';

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

        // 2. Tambahkan konfigurasi AutoImport untuk fungsi/API Vant (seperti showToast)
        AutoImport({
            resolvers: [VantResolver()],
            dts: 'resources/js/auto-imports.d.ts', // Otomatis generate file tipe data
        }),

        // 3. Tambahkan konfigurasi Components untuk komponen Vue Vant (seperti <van-button>)
        Components({
            resolvers: [VantResolver()],
            dts: 'resources/js/components.d.ts', // Otomatis generate file tipe data
        }),
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
