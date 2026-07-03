import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from './ziggy.js'
import { Toast } from 'vant'

createInertiaApp({
    withApp(app) {
        // Pasang ZiggyVue plugin dan inject objek Ziggy rute ke dalamnya
        app.use(ZiggyVue, Ziggy)
        app.use(Toast)
    },
})
