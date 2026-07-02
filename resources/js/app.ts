import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from './ziggy.js'

createInertiaApp({
    withApp(app) {
        // Pasang ZiggyVue plugin dan inject objek Ziggy rute ke dalamnya
        app.use(ZiggyVue, Ziggy)
    },
})
