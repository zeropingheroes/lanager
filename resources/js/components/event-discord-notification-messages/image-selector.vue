<script setup>
import { ref, computed } from 'vue';
import { formatSize } from '../../utils/formatSize.js';
import SelectionImageRow from './selection-image-row.vue';
import LibraryImageRow from './library-image-row.vue';

const props = defineProps({
    availableImages: { type: Array, default: () => [] },
    selectedImages: { type: Array, default: () => [] },
    imagesUrl: { type: String, required: true },
    maxImages: { type: Number, required: true },
    maxFileBytes: { type: Number, required: true },
    maxTotalBytes: { type: Number, required: true },
});

const selectedPaths = new Set(props.selectedImages.map(img => img.path));

const library = ref(props.availableImages.filter(img => !selectedPaths.has(img.path)));
const selection = ref([...props.selectedImages]);
const filterQuery = ref('');

const imageLibraryEmpty = computed(() => props.availableImages.length === 0 && props.selectedImages.length === 0);

const filteredLibrary = computed(() => {
    if (!filterQuery.value) return library.value;
    const q = filterQuery.value.toLowerCase();
    return library.value.filter(img => img.filename.toLowerCase().includes(q));
});

const totalSize = computed(() => selection.value.reduce((sum, img) => sum + img.size, 0));

const limitReached = computed(() => {
    if (selection.value.length >= props.maxImages) return 'count';
    if (totalSize.value >= props.maxTotalBytes) return 'size';
    return null;
});

function canAdd(img) {
    if (img.size > props.maxFileBytes) return false;
    if (selection.value.length >= props.maxImages) return false;
    return totalSize.value + img.size <= props.maxTotalBytes;

}

function addImage(img) {
    library.value = library.value.filter(i => i.path !== img.path);
    selection.value.push(img);
}

function removeImage(img) {
    selection.value = selection.value.filter(i => i.path !== img.path);
    library.value = [...library.value, img].sort((a, b) => a.filename.localeCompare(b.filename));
}

function isFirst(img) {
    return selection.value[0] === img;
}

function isLast(img) {
    return selection.value[selection.value.length - 1] === img;
}

function moveUp(img) {
    const index = selection.value.indexOf(img);
    if (index <= 0) return;
    const arr = [...selection.value];
    [arr[index - 1], arr[index]] = [arr[index], arr[index - 1]];
    selection.value = arr;
}

function moveDown(img) {
    const index = selection.value.indexOf(img);
    if (index === -1 || index >= selection.value.length - 1) return;
    const arr = [...selection.value];
    [arr[index], arr[index + 1]] = [arr[index + 1], arr[index]];
    selection.value = arr;
}
</script>

<template>
    <input v-for="img in selection" :key="img.path" type="hidden" name="image_paths[]" :value="img.path">

    <div class="selection-panel">
        <ul class="list-group selection-list">
            <li v-if="selection.length === 0" class="list-group-item hint">
                {{ $t('phrase.discord-no-images-added') }}
            </li>
            <SelectionImageRow
                v-for="img in selection"
                :key="img.path"
                :img="img"
                :is-first="isFirst(img)"
                :is-last="isLast(img)"
                :max-file-bytes="props.maxFileBytes"
                @move-up="moveUp(img)"
                @move-down="moveDown(img)"
                @remove="removeImage(img)"
            />
        </ul>
        <div class="form-text selection-stats">
            {{ $t('phrase.discord-image-count-status', { count: selection.length, max: props.maxImages }) }}
            {{ $t('phrase.discord-image-total-size', { size: formatSize(totalSize), total: formatSize(props.maxTotalBytes) }) }}
            {{ $t('phrase.discord-image-size-limits', { perFile: formatSize(props.maxFileBytes) }) }}
            <span v-if="limitReached === 'count'" class="limit-warning">{{ $t('phrase.discord-image-count-limit-reached', { max: props.maxImages }) }}</span>
            <span v-else-if="limitReached === 'size'" class="limit-warning">{{ $t('phrase.discord-image-size-limit-reached', { limit: formatSize(props.maxTotalBytes) }) }}</span>
        </div>
    </div>

    <div>
        <template v-if="imageLibraryEmpty">
            <p class="no-library-message">{{ $t('phrase.no-images-in-library') }}</p>
        </template>
        <template v-else>
            <input
                v-model="filterQuery"
                type="text"
                class="form-control form-control-sm filter-input"
                :placeholder="$t('phrase.discord-filter-by-filename')"
            >
            <ul class="list-group library-list">
                <li v-if="filteredLibrary.length === 0 && filterQuery" class="list-group-item hint">
                    {{ $t('phrase.discord-no-images-match-filter') }}
                </li>
                <li v-else-if="filteredLibrary.length === 0" class="list-group-item hint">
                    {{ $t('phrase.discord-all-images-added') }}
                </li>
                <LibraryImageRow
                    v-for="img in filteredLibrary"
                    :key="img.path"
                    :img="img"
                    :can-add="canAdd(img)"
                    :max-file-bytes="props.maxFileBytes"
                    @add="addImage(img)"
                />
            </ul>
        </template>
        <div class="form-text"><a :href="imagesUrl" target="_blank">{{ $t('title.upload-images') }}</a></div>
    </div>
</template>

<style scoped>
.selection-panel {
    margin-bottom: 0.5rem;
}
.selection-list {
    margin-bottom: 0.25rem;
}
.selection-stats {
    margin-top: 0.25rem;
}
.limit-warning {
    color: rgba(var(--bs-warning-rgb), 1);
    margin-left: 0.25rem;
}
.hint {
    font-size: 14px;
    color: var(--bs-secondary-color);
}
.no-library-message {
    font-size: 14px;
    color: var(--bs-secondary-color);
    margin-bottom: 0.25rem;
}
.filter-input {
    margin-bottom: 0.25rem;
}
.library-list {
    max-height: 220px;
    overflow-y: auto;
    margin-bottom: 0.25rem;
}
</style>
