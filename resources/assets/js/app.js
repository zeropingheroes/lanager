/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('events', require('./components/dashboard/events.vue'));
Vue.component('event', {
        props: ['name', 'type'],
        template: `<tr>
            <td><span class="badge badge-primary">Now</span></td>
            <td>{{ name }}</td>
            <td>{{ type }}</td>
            <td>Ending in 16 minutes</td>
        </tr>`,
    }
);
