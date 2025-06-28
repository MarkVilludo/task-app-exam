import './bootstrap';
import '@assets/css/bootstrap/bootstrap.min.css';
import '@assets/css/app.css';
import '@resources/css/app.css';


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import { plugin as VueTippy } from 'vue-tippy';
import 'tippy.js/dist/tippy.css';


// Toastr initialization
import toastr from 'toastr';
import 'toastr/build/toastr.css';

// Toastr does not have an install method, you might need to configure it differently.
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(ZiggyVue);
        app.use(VueSweetalert2); //additional
        app.use(VueTippy); //additional

        // Toastr does not have an install method. It should be configured globally.
        app.config.globalProperties.$toastr = toastr;

        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});