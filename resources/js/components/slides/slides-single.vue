<script setup>
import {ref, onMounted, onUnmounted} from 'vue';
import axios from 'axios';
import Slide from './slide.vue'

const props = defineProps(['id', 'lan_id']);

const slide = ref({content: ''});

const update = async () => {
    try {
        console.log('Getting slide');
        const response = await axios.get(`lans/${props.lan_id}/slides/${props.id}`);
        console.log('Displaying single slide');
        slide.value = response.data.data;
    } catch (error) {
        console.log('Error getting slide:', error.response);
    }
};

onMounted(() => {
    update();
});

</script>

<template>
    <div class="container-1920x1080">
        <slide :content="slide.content"></slide>
    </div>
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
