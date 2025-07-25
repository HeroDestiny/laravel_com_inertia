/// <reference types="vitest" />
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
    test: {
        environment: 'jsdom',
        globals: true,
        coverage: {
            provider: 'v8',
            reporter: ['text', 'json', 'html', 'lcov'],
            reportsDirectory: './coverage',
            exclude: [
                'node_modules/',
                'tests/',
                'coverage/',
                '**/*.d.ts',
                '**/*.config.{ts,js}',
                'vite.config.ts',
                'tailwind.config.js',
                'postcss.config.js',
                'eslint.config.js',
            ],
        },
    },
});
