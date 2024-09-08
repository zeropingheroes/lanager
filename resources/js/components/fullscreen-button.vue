<template>
    <transition name="fade">
        <div id="fullscreen-button" class="btn" v-show="visible" @click="toggleFullscreen">
            <span class="fa fa-solid fa-expand" :title="Fullscreen" aria-hidden="true"></span>
        </div>
    </transition>
</template>

<script setup>
import {ref, onMounted, onBeforeUnmount} from 'vue';

const visible = ref(false);

const toggleFullscreen = () => {
    if (
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.msFullscreenElement
    ) {
        exitFullscreen();
    } else {
        enterFullscreen();
    }
};

const enterFullscreen = () => {
    const d = document.documentElement;
    if (d.requestFullscreen) {
        d.requestFullscreen();
    } else if (d.mozRequestFullScreen) {
        d.mozRequestFullScreen();
    } else if (d.webkitRequestFullscreen) {
        d.webkitRequestFullscreen();
    } else if (d.msRequestFullscreen) {
        d.msRequestFullscreen();
    }
};

const exitFullscreen = () => {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
};

const showFullscreenButton = () => {
    if (!visible.value) {
        visible.value = true;
        setTimeout(() => {
            visible.value = false;
        }, 2000);
    }
};

onMounted(() => {
    window.addEventListener('mousemove', showFullscreenButton);
});

onBeforeUnmount(() => {
    window.removeEventListener('mousemove', showFullscreenButton);
});
</script>

<style>
div#fullscreen-button {
    font-size: 400%;
    position: absolute;
    bottom: 15px;
    right: 15px;
}
</style>
