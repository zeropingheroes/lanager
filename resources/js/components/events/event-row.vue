<script setup>
import { computed } from 'vue';
import moment from 'moment';
import EventStatus from './event-status.vue';
import EventStartAndEnd from './event-start-and-end.vue';
import EventRelativeTime from './event-relative-time.vue';

const props = defineProps({
    name: String,
    start: [String, Date],
    end: [String, Date],
    now: [String, Date],
});

const status = computed(() => {
    if (moment(props.start).isBefore(props.now) && moment(props.end).isAfter(props.now)) {
        return 'present';
    }

    if (moment(props.start).isAfter(props.now) && moment(props.end).isAfter(props.now)) {
        return 'future';
    }

    return 'past';
});
</script>

<template>
    <tr>
        <td class="event-status">
            <EventStatus :status="status" />
        </td>

        <td class="event-name">
            {{ name }}
        </td>

        <td class="event-start-end">
            <EventStartAndEnd :start="start" :end="end" />
        </td>

        <td class="event-relative-time">
            <EventRelativeTime
                :status="status"
                :start="start"
                :end="end"
                :now="now"
            />
        </td>
    </tr>
</template>

<style>
td {
    font-size: 200%;
}

td.event-name {
    font-size: 200%;
    font-weight: bold;
    padding-left: 30px;
    padding-right: 30px;
}
</style>
