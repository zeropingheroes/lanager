import Slides from '../components/slides/slides.vue'
import FullscreenButton from '../components/fullscreen-button.vue'

const app = createApp({});
app.component('Slides', Slides);
app.component('FullscreenButton', FullscreenButton);
app.mount('#app');
