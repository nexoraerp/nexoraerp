import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const storedTheme = localStorage.getItem('nexora-theme') ?? 'light';

document.documentElement.classList.toggle('dark', storedTheme === 'dark');


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {

        const app = createApp({
            render: () => h(App, props),
        });

        app.use(plugin);

        app.use(ZiggyVue);

        app.mount(el);

    },

    progress: {
        color: '#4B5563',
    },
});
