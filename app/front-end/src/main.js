import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import * as labsComponents from 'vuetify/labs/components'
import '@mdi/font/css/materialdesignicons.css'
import ru from 'vuetify/lib/locale/ru'
import VueTheMask from 'vue-the-mask'



const app = createApp(App)

const vuetify = createVuetify({
    components: {
        ...components,
        ...labsComponents,
    },
    icons: {
        defaultSet: 'mdi', // This is already the default value - only for display purposes
    },
    directives,
    lang: {
        locales: { ru },
        current: 'ru'
    }
})

app.use(router).use(vuetify).use(VueTheMask)
app.mount('#app')

