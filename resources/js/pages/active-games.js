import ActiveGames from '../components/active-games/active-games.vue'
import {i18nVue} from 'laravel-vue-i18n'

const app = createApp({});
app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../../lang/*.json');
        return await langs[`../../lang/php_${lang}.json`]();
    }
})
app.component('ActiveGames', ActiveGames);
app.mount('#app');
