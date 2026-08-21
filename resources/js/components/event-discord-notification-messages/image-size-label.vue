<script setup>
import { computed } from 'vue';
import { formatSize } from '../../utils/formatSize.js';

const props = defineProps({
    size: { type: Number, required: true },
    maxFileBytes: { type: Number, required: true },
});

const oversized = computed(() => props.size > props.maxFileBytes);
</script>

<template>
    <span class="size-label" :class="{ oversized: oversized }">{{ formatSize(size) }}</span>
</template>

<style scoped>
.size-label {
    font-size: 14px;
    white-space: nowrap;
    color: var(--bs-secondary-color);
}
.size-label.oversized {
    color: rgba(var(--bs-warning-rgb), 1);
}
</style>
