<template>
    <div class="slide-container">
        <img
             class="image-slide"
             v-if="type() === 'image'"
             :src="content">
        <iframe
            class="website-slide"
            v-else-if="type() === 'website'"
            :src="content"
            scrolling="no"
            seamless>
        </iframe>
        <vue-markdown
            class="markdown-slide"
            v-else
            :options="{linkify: true, breaks: true}"
            :source="content">
        </vue-markdown>
    </div>
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
                    return 'website';
                } else {
                    return 'markdown';
                }
            },
        },
    }
</script>
