<script setup>
import {computed} from 'vue';

const props = defineProps({
    size: {
        type: String,
        validator: (value) => ['small', 'medium', 'large'].includes(value),
        default: 'medium',
    },
    user: {
        type: Object,
        required: true,
    },
});

const src = computed(() => props.user.oauth_accounts[0].avatar[props.size]);
</script>

<template>
    <img :src="src" :title="user.username"/>
</template>
