import axios from 'axios';
import * as Popper from '@popperjs/core'
import 'bootstrap';
import {TempusDominus, Namespace} from '@eonasdan/tempus-dominus';
import {createApp} from 'vue';
import ClipboardJS from 'clipboard';
import {I18n} from 'laravel-vue-i18n';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = document.head.querySelector('meta[name="api-base-url"]').content;

window.Popper = Popper;

window.TempusDominus = TempusDominus;
window.Namespace = Namespace;

window.createApp = createApp;

window.ClipboardJS = ClipboardJS;

window.I18n = new I18n({
    resolve: async lang => {
        const langs = import.meta.glob('../lang/*.json');
        return await langs[`../lang/php_${lang}.json`]();
    }
});
