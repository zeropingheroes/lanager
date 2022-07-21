/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Define any JavaScript that should be run on every page
 */
window.addEventListener('load', function () {

    // Set up copy to clipboard for any elements with that class
    var clipboard = new Clipboard('.copy-to-clipboard');
    clipboard.on('error', function (e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    // Confirm deletion for any elements with that class
    $('.confirm-deletion').on('submit', function () {
        return confirm(window.lang.get('phrase.are-you-sure-delete'));
    });
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


/* Buttons */
import FullscreenButton from './components/fullscreen-button.vue';

Vue.component("fullscreen-button", FullscreenButton);

/* General Purpose */
Vue.component('vue-markdown', require('vue-markdown').default);

/* Events */
import Events from './components/events/events.vue';

Vue.component("events", Events);

import Event from './components/events/event.vue';

Vue.component("event", Event);

import EventStatus from './components/events/event-status.vue';

Vue.component("event-status", EventStatus);

import EventRelativeTime from './components/events/event-relative-time.vue';

Vue.component("event-relative-time", EventRelativeTime);

import EventStartAndEnd from './components/events/event-start-and-end.vue';

Vue.component("event-start-and-end", EventStartAndEnd);

import EventSchedule from './components/events/event-schedule.vue';

Vue.component("event-schedule", EventSchedule);


/* Games */
import GameBanner from './components/games/game-banner.vue';

Vue.component('game-banner', GameBanner);

import ActiveGames from './components/active-games/active-games.vue';

Vue.component('active-games', ActiveGames);

import ActiveGame from './components/active-games/active-game.vue';

Vue.component('active-game', ActiveGame);

/* Users */
import UserAvatar from './components/users/user-avatar.vue';

Vue.component('user-avatar', UserAvatar);

/* Slides */
import Slides from './components/slides/slides.vue';

Vue.component('slides', Slides);

import SlidesSingle from './components/slides/slides-single.vue';

Vue.component('slides-single', SlidesSingle);

import Slide from './components/slides/slide.vue';

Vue.component('slide', Slide);
