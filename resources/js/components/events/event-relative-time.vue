<script setup>
import { ref, watch, onMounted } from 'vue';
import moment from 'moment';
import {trans} from 'laravel-vue-i18n';

const props = defineProps(['status', 'start', 'end', 'now']);

const relativeTimeText = ref('');

const updateRelativeTimeText = () => {
    switch (props.status) {
        case 'past':
            relativeTimeText.value = trans('phrase.ended') + ' ' + moment(props.end).fromNow();
            break;
        case 'present':
            relativeTimeText.value = trans('phrase.started') + ' ' + moment(props.start).fromNow();
            break;
        case 'future':
            relativeTimeText.value = trans('phrase.starting') + ' ' + moment(props.start).fromNow();
            break;
        default:
            relativeTimeText.value = trans('phrase.unknown');
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
