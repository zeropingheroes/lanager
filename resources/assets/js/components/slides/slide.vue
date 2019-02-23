<template>
    <img v-if="type() === 'image'" :src="content" class="image-only">
    <iframe v-else-if="type() === 'url'" :src="content" class="url-only" scrolling="no" seamless></iframe>
    <vue-markdown v-else :source="content" class="slide-content"></vue-markdown>
</template>

<script>
    export default {
        props: ['content'],
        methods: {
            type() {
                const isImageUrl = require('is-image-url');
                const isUrl = require('is-url');
                if(isImageUrl(this.content)) {
                    return 'image';
                } else if(isUrl(this.content)) {
                    return 'url';
                } else {
                    return 'markdown';
                }
            },
        },
    }
</script>