<script setup>
import { computed } from 'vue';
import moment from 'moment';

// Define props
const props = defineProps(['start', 'end']);

// Computed property for start and end time formatting
const startAndEnd = computed(() => {
    const start = moment(props.start);
    const end = moment(props.end);

    // Determine start format
    const startFormat = start.minute() === 0 ? 'ddd ha' : 'ddd h:mma';

    // Determine end format
    let endFormat = end.minute() === 0 ? 'ha' : 'h:mma';

    // Adjust end format if start and end are on different days
    if (start.day() !== end.day()) {
        endFormat = 'ddd ' + endFormat;
    }

    // Return formatted start and end times
    return `${start.format(startFormat)} - ${end.format(endFormat)}`;
});
</script>

<template>
    <span>{{ startAndEnd }}</span>
</template>
