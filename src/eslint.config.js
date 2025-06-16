import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';
import prettier from 'eslint-config-prettier';
import vue from 'eslint-plugin-vue';

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'tailwind.config.js', 'resources/js/components/ui/*'],
    },
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'off',

            'no-restricted-properties': [
                'error',
                { object: 'window', property: 'escape', message: '`window.escape` é obsoleto.' },
                { object: 'window', property: 'unescape', message: '`window.unescape` é obsoleto.' },
                { object: 'document', property: 'write', message: '`document.write` é inseguro e obsoleto.' },
                { object: 'Vue', property: 'set', message: '`Vue.set` foi removido no Vue 3.' },
                { object: 'Vue', property: 'delete', message: '`Vue.delete` foi removido no Vue 3.' },
            ],
            'no-restricted-globals': [
                'error',
                { name: 'escape', message: '`escape` é uma função global obsoleta.' },
                { name: 'unescape', message: '`unescape` é uma função global obsoleta.' },
            ],
        },
    },
    prettier,
);
