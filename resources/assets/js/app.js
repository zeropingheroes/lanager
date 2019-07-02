/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Define any JavaScript that should be run on every page
 */
window.addEventListener('load', function() {

    // Set up copy to clipboard for any elements with that class
    var clipboard = new Clipboard('.copy-to-clipboard');
    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    // Confirm deletion for any elements with that class
    $('.confirm-deletion').on('submit', function(){
        return confirm(window.lang.get('phrase.are-you-sure-delete'));
    });
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* Buttons */
Vue.component('fullscreen-button',      require('./components/fullscreen-button.vue'));

/* General Purpose */
Vue.component('vue-markdown',           require('vue-markdown').default);

/* Events */
Vue.component('events',                 require('./components/events/events.vue'));
Vue.component('event',                  require('./components/events/event.vue'));
Vue.component('event-status',           require('./components/events/event-status.vue'));
Vue.component('event-relative-time',    require('./components/events/event-relative-time.vue'));
Vue.component('event-start-and-end',    require('./components/events/event-start-and-end.vue'));
Vue.component('event-schedule',         require('./components/events/event-schedule.vue'));

/* Games */
Vue.component('game-banner',            require('./components/games/game-banner.vue'));
Vue.component('active-games',           require('./components/active-games/active-games.vue'));
Vue.component('active-game',            require('./components/active-games/active-game.vue'));

/* Users */
Vue.component('user-avatar',            require('./components/users/user-avatar.vue'));
Vue.component('game-search-suggest',    require('./components/users/game-search-suggest.vue'));
Vue.component('favourite-games',        require('./components/users/favourite-games.vue'));
Vue.component('favourite-game',         require('./components/users/favourite-game.vue'));

/* Slides */
Vue.component('slides',                 require('./components/slides/slides.vue'));
Vue.component('slides-single',          require('./components/slides/slides-single.vue'));
Vue.component('slide',                  require('./components/slides/slide.vue'));