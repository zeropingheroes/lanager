<script setup>
defineProps({
    href: {type: String, required: true},
});
</script>

<template>
    <a :href="href"
       target="_blank"
       rel="noopener noreferrer"
    >
        <i class="fa-solid fa-up-right-from-square"></i>
    </a>
</template>
