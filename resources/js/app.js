import './bootstrap';

import.meta.glob([
    '../images/**',
]);

function mountVue() {
    const appElement = document.getElementById('app');
    if (appElement) {
        const app = createApp({});
        app.mount('#app');
        console.log('Vue 3 app mounted successfully');
    }
}

// Only mount Vue if there is an element with the ID "app"
document.addEventListener('DOMContentLoaded', mountVue);
