<script setup>
import ImageSizeLabel from './image-size-label.vue';

defineProps({
    img: { type: Object, required: true },
    isFirst: { type: Boolean, required: true },
    isLast: { type: Boolean, required: true },
    maxFileBytes: { type: Number, required: true },
});

const emit = defineEmits(['move-up', 'move-down', 'remove']);
</script>

<template>
    <li class="list-group-item image-row">
        <button
            type="button"
            class="btn btn-sm btn-secondary reorder-btn"
            :disabled="isFirst"
            @click="emit('move-up')"
        ><i class="fa-solid fa-arrow-up"></i></button>
        <button
            type="button"
            class="btn btn-sm btn-secondary reorder-btn"
            :disabled="isLast"
            @click="emit('move-down')"
        ><i class="fa-solid fa-arrow-down"></i></button>
        <img
            :src="img.url"
            :alt="img.filename"
            class="thumbnail"
        >
        <span class="filename" :title="img.filename">{{ img.filename }}</span>
        <ImageSizeLabel :size="img.size" :max-file-bytes="maxFileBytes" />
        <button
            type="button"
            class="btn btn-sm btn-outline-danger remove-btn"
            @click="emit('remove')"
        ><i class="fa-solid fa-minus"></i></button>
    </li>
</template>

<style scoped>
.image-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
}
.thumbnail {
    width: 56px;
    height: 42px;
    object-fit: cover;
    border-radius: var(--bs-border-radius);
    flex-shrink: 0;
}
.filename {
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 14px;
}
.reorder-btn {
    font-size: 14px;
}
.remove-btn {
    font-size: 14px;
}
</style>
