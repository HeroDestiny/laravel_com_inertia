import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createSSRApp, h } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')) as any,
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            // Configure Ziggy for SSR...
            const ziggyConfig = {
                ...((page.props.ziggy as any) || {}),
                location: new URL((page.props.ziggy as any)?.location || 'https://localhost'),
            };

            // Create route function...
            const route = (...args: any[]) => (ziggyRoute as any)(...args, ziggyConfig);

            // Make route function available globally...
            (app.config.globalProperties as any).route = route;

            // Make route function available globally for SSR...
            if (typeof globalThis !== 'undefined') {
                (globalThis as any).route = route;
            }

            app.use(plugin);

            return app;
        },
    }),
);
