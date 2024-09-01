<template>
    <span :class="className">{{ statusName }}</span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps(['status']);

const statusName = computed(() => {
    switch (props.status) {
        case 'past':
            return 'Ended';
        case 'present':
            return 'Now';
        case 'future':
            return 'Next';
        default:
            return 'Unknown';
    }
});

const className = computed(() => {
    switch (props.status) {
        case 'past':
            return 'badge text-bg-danger';
        case 'present':
            return 'badge text-bg-primary';
        case 'future':
            return 'badge text-bg-info';
        default:
            return 'badge text-bg-secondary';
    }
});
</script>
