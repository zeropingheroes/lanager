<script setup>
const props = defineProps({
    type: {type: String, required: true},
    titleKey: {type: String, required: true},
    field: {type: String, required: true},
    items: {type: Array, required: true},
    modelValue: {type: Set, required: true},
});

const emit = defineEmits(['update:modelValue']);

function toggle(id) {
    const next = new Set(props.modelValue);

    if (next.has(id)) {
        next.delete(id);
    } else {
        next.add(id);
    }

    emit('update:modelValue', next);
}

function toggleAll() {
    if (props.modelValue.size === props.items.length) {
        emit('update:modelValue', new Set());
    } else {
        emit('update:modelValue', new Set(props.items.map(item => item.id)));
    }
}
</script>

<template>
    <div class="lan-clone-selection-table mb-4"
         :data-selection-table="type"
         v-if="items.length"
    >
        <input v-for="id in modelValue"
               :key="id"
               type="hidden"
               :name="`${field}[]`"
               :value="id"
        >
        <h2 class="h5 mb-2">{{ $t(titleKey) }}</h2>
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th class="text-center narrow-column">
                    <input class="form-check-input"
                           type="checkbox"
                           data-action="toggle-all"
                           :checked="modelValue.size === items.length"
                           @change="toggleAll"
                    >
                </th>
                <slot name="header"></slot>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in items"
                :key="item.id"
            >
                <td class="text-center narrow-column">
                    <input class="form-check-input"
                           type="checkbox"
                           :data-item-id="item.id"
                           :checked="modelValue.has(item.id)"
                           @change="toggle(item.id)"
                    >
                </td>
                <slot :item="item"></slot>
            </tr>
            </tbody>
        </table>
    </div>
</template>
