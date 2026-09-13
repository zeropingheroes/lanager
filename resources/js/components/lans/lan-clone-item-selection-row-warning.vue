<script setup>
defineProps({
    visible: {type: Boolean, required: true},
    itemId: {type: [String, Number], required: true},
});
</script>

<template>
    <span v-if="visible"
          class="text-warning"
          data-out-of-range
          :data-item-id="itemId"
    >
        <i class="fa-solid fa-triangle-exclamation"></i>
        {{ $t('phrase.out-of-time-range') }}
    </span>
</template>
