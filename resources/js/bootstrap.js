import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import * as Popper from '@popperjs/core'
window.Popper = Popper;

import 'bootstrap'

import {TempusDominus, Namespace} from '@eonasdan/tempus-dominus';
window.TempusDominus = TempusDominus;
window.Namespace = Namespace;

import {createApp} from 'vue';

window.createApp = createApp;
