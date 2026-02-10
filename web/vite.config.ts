import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/APP/app.tsx',
                'resources/js/PPDT/app.tsx',
            ],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react({
            babel: {
                plugins: ['babel-plugin-react-compiler'],
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@shared': path.resolve(__dirname, 'resources/js/shared'),
            '@actions': path.resolve(__dirname, 'resources/js/actions'),
            '@app': path.resolve(__dirname, 'resources/js/APP'),
            '@ppdt': path.resolve(__dirname, 'resources/js/PPDT'),
        },
    },
    esbuild: {
        jsx: 'automatic',
    },
});
