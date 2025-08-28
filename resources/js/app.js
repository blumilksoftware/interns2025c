import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createI18n } from 'vue-i18n'
import VueEasyLightbox from 'vue-easy-lightbox'

const plModules = import.meta.glob('./lang/pl/*.json', { eager: true })
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

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(VueEasyLightbox)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
