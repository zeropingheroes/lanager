<template>
    <img v-if="type() === 'image'" :src="slide.content" class="image-only">
    <iframe v-else-if="type() === 'url'" :src="slide.content" class="url-only" scrolling="no" seamless></iframe>
    <vue-markdown v-else :source="slide.content" class="slide-content"></vue-markdown>
</template>

<script>
    export default {
        props: ['slide'],
        methods: {
            type() {
                const isImageUrl = require('is-image-url');
                const isUrl = require('is-url');
                if(isImageUrl(this.slide.content)) {
                    return 'image';
                } else if(isUrl(this.slide.content)) {
                    return 'url';
                } else {
                    return 'markdown';
                }
            },
        },
    }
</script>