<script setup>
import {computed} from 'vue';

const props = defineProps({
    size: {
        type: String,
        validator: (value) => ['small', 'medium', 'large'].includes(value),
        default: 'medium',
    },
    game: {
        type: Object,
        required: true,
    },
});

const src = computed(() => props.game.logo[props.size]);
</script>

<template>
    <img :src="src" :title="game.name"/>
</template>
