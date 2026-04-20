import Slides from '../components/slides/slides.vue'
import SlidesSingle from '../components/slides/slides-single.vue'
import FullscreenButton from '../components/fullscreen-button.vue'
import {i18nVue} from "laravel-vue-i18n";

const app = createApp({});
app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../../lang/*.json');
        return await langs[`../../lang/${lang}.json`]();
    }
})
app.component('Slides', Slides);
app.component('SlidesSingle', SlidesSingle);
app.component('FullscreenButton', FullscreenButton);
app.mount('#app');
