/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
import langs from './langs.js';
import Vue from 'vue'

try {
    window.$ = window.jQuery = require('jquery');
    window.moment = require('moment');
    window.Clipboard = require('clipboard');
    window.Vue = Vue;

    var Lang = require('lang.js');
    window.lang = new Lang({messages: langs});

    require('tempusdominus-bootstrap-4');
    require('bootstrap');
} catch (e) {
    console.error(e);
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
import FullCalendar from 'vue-full-calendar';
Vue.use(FullCalendar);

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = document.head.querySelector('meta[name="api-base-url"]').content;

if(document.head.querySelector("meta[name=api-user-id]")) {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.head.querySelector("meta[name=api-token]").content;
    window.userId = Number(document.head.querySelector("meta[name=api-user-id]").content);
}


/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
