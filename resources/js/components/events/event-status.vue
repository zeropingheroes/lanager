<script setup>
import { computed } from 'vue';
import {trans} from 'laravel-vue-i18n';

const props = defineProps(['status']);

const statusName = computed(() => {
    switch (props.status) {
        case 'past':
            return trans('phrase.ended');
        case 'present':
            return trans('phrase.now');
        case 'future':
            return trans('phrase.next');
        default:
            return trans('phrase.unknown');
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

<template>
    <span :class="className">{{ statusName }}</span>
</template>
