<script setup>
import {ref, onMounted, onUnmounted} from 'vue';
import axios from 'axios';
import Slide from './slide.vue'

const props = defineProps(['lan_id']);

const slides = ref([]);
const indexToShow = ref(null);

let updateInterval;
let cycleTimeout;

const update = async () => {
    try {
        const response = await axios.get(`lans/${props.lan_id}/slides/`);
        slides.value = response.data.data;

        if (slides.value.length !== 0 && indexToShow.value === null) {
            cycle();
        }
    } catch (error) {
        console.log('Error getting slides', error);
    }
};

const cycle = () => {
    if (slides.value.length === 0) {
        indexToShow.value = null;
    } else {
        if (indexToShow.value === null) {
            indexToShow.value = 0;
        } else {
            indexToShow.value = (indexToShow.value + 1) % slides.value.length;
        }

        cycleTimeout = setTimeout(() => {
            cycle();
        }, slides.value[indexToShow.value].duration * 1000);
    }
};

onMounted(() => {
    update();
    updateInterval = setInterval(update, 30000);
});

onUnmounted(() => {
    clearInterval(updateInterval);
    clearTimeout(cycleTimeout);
});
</script>

<template>
    <transition-group name="fade" class="container-1920x1080" tag="div">
        <slide
            v-for="(slide, index) in slides"
            :key="slide.id"
            :content="slide.content"
            v-show="indexToShow === index"
        ></slide>
    </transition-group>
</template>

<style>
div.container-1920x1080 {
    /* set container initial and maximum sizes */
    max-width: 1920px;
    width: 1920px;
    max-height: 1080px;
    height: 1080px;
}
</style>
