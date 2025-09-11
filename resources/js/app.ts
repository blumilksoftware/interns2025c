import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createI18n } from 'vue-i18n'
import VueEasyLightbox from 'vue-easy-lightbox'

const plModules = import.meta.glob<Record<string, any>>('./lang/pl/*.json', { eager: true })
const pl = Object.values(plModules).reduce((merged, mod) => {
  const payload = (mod && mod.default) ? mod.default : mod
  return { ...merged, ...payload }
}, {})

const i18n = createI18n({
  legacy: false,
  locale: 'pl',
  fallbackLocale: 'en',
  messages: { pl },
})

const appName = import.meta.env.VITE_APP_NAME || 'ŁapGo'
const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
  title: (title: string) => `${title} - ${appName}`,
  resolve: (name: string) => resolvePageComponent(`./Pages/${name}.vue`, pages) as any,
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(VueEasyLightbox)

    app.mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
