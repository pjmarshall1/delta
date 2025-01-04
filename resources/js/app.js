import '../css/app.css';
import './bootstrap';

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, h} from 'vue';
import {createPinia} from "pinia";
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import NumeralPlugin from './Plugins/numeral';
import DayJsPlugin from './Plugins/dayjs';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .mixin({methods: {route}})
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia())
            .use(NumeralPlugin)
            .use(DayJsPlugin);

        app.mount(el);
    },
    progress: {
        color: '#6366f1',
    },
});
