<script setup>
import ImageSizeLabel from './image-size-label.vue';

defineProps({
    img: { type: Object, required: true },
    canAdd: { type: Boolean, required: true },
    maxFileBytes: { type: Number, required: true },
});

const emit = defineEmits(['add']);
</script>

<template>
    <li class="list-group-item image-row">
        <button
            type="button"
            class="btn btn-sm btn-primary add-btn"
            :disabled="!canAdd"
            @click="emit('add')"
        ><i class="fa-solid fa-plus"></i></button>
        <img
            :src="img.url"
            :alt="img.filename"
            class="thumbnail"
        >
        <span class="filename" :title="img.filename">{{ img.filename }}</span>
        <ImageSizeLabel :size="img.size" :max-file-bytes="maxFileBytes" />
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
.add-btn {
    font-size: 14px;
}
</style>
