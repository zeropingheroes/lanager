<script setup>
import { ref, watch, onMounted } from 'vue';
import moment from 'moment';

const props = defineProps(['status', 'start', 'end', 'now']);

const relativeTimeText = ref('');

const updateRelativeTimeText = () => {
    switch (props.status) {
        case 'past':
            relativeTimeText.value = 'Ended ' + moment(props.end).fromNow();
            break;
        case 'present':
            relativeTimeText.value = 'Started ' + moment(props.start).fromNow();
            break;
        case 'future':
            relativeTimeText.value = 'Starting ' + moment(props.start).fromNow();
            break;
        default:
            relativeTimeText.value = 'Unknown';
    }
};

onMounted(() => {
    updateRelativeTimeText();
});

watch(() => props.now, updateRelativeTimeText);
</script>

<template>
    <span>{{ relativeTimeText }}</span>
</template>
