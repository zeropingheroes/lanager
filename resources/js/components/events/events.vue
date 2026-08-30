<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import moment from 'moment';
import axios from 'axios';
import EventRow from './event-row.vue';

const props = defineProps({
    lan_id: [String, Number],
    limit: {
        type: [String, Number],
        default: 5,
    },
});

const time = ref(moment().format("HH:mm"));
const now = ref(moment());
const events = ref([]);

const update = () => {
    time.value = moment().format("HH:mm");
    now.value = moment();
    axios.get(`lans/${props.lan_id}/events`)
        .then((response) => {
            events.value = response.data.data
                .filter((event) => moment(event.end).isAfter(now.value))
                .slice(0, props.limit);
        })
        .catch((error) => {
            console.log('Error getting events', error);
        });
};

let intervalId

onMounted(() => {
    update()
    intervalId = setInterval(update, 30000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <table class="table">
        <tbody>
        <EventRow
            v-for="event in events"
            :key="event.id"
            v-bind="event"
            :now="now"
        ></EventRow>
        </tbody>
    </table>
</template>
