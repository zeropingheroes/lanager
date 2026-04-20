<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import moment from 'moment';
import axios from 'axios';
import event from './event.vue';

const time = ref(moment().format("HH:mm"));
const now = ref(moment());
const events = ref([]);

const update = () => {
    time.value = moment().format("HH:mm");
    now.value = moment();
    axios.get(`events?after=${now.value.format()}&limit=5`)
        .then((response) => {
            events.value = response.data.data;
        })
        .catch((error) => {
            console.log('Error getting events', error);
        });
};

let intervalId;
onMounted(() => {
    update();
    intervalId = setInterval(update, 60000);
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
});
</script>

<template>
    <table class="table">
        <tbody>
        <event
            v-for="event in events"
            :key="event.id"
            v-bind="event"
            :now="now"
        />
        </tbody>
    </table>
</template>
