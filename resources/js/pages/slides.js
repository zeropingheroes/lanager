import Slides from '../components/slides/slides.vue'
import SlidesSingle from '../components/slides/slides-single.vue'
import FullscreenButton from '../components/fullscreen-button.vue'

const app = createApp({});
app.component('Slides', Slides);
app.component('SlidesSingle', SlidesSingle);
app.component('FullscreenButton', FullscreenButton);
app.mount('#app');
