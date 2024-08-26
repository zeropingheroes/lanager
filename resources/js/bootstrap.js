import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import * as Popper from '@popperjs/core'

window.Popper = Popper;

import 'bootstrap'

import {TempusDominus} from '@eonasdan/tempus-dominus';

window.TempusDominus = TempusDominus;
