import Events from '../components/events/events.vue'
import {i18nVue} from "laravel-vue-i18n";

const app = createApp({});
app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../../lang/*.json');
        return await langs[`../../lang/${lang}.json`]();
    }
})
app.component('Events', Events);
app.mount('#app');
