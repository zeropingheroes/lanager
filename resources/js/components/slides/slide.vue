<script setup>
import {computed} from 'vue';
import VueMarkdown from 'vue-markdown-render'

const props = defineProps(['content']);

const isValidHttpUrl = (string) => {
    let url;

    try {
        url = new URL(string);
    } catch (_) {
        return false;
    }

    return url.protocol === "http:" || url.protocol === "https:";
}

const type = computed(() => {
    if (isValidHttpUrl(props.content)) {
        return 'website';
    } else {
        return 'markdown';
    }
});
</script>

<template>
    <div class="slide-container">
        <iframe
            v-if="type === 'website'"
            class="website-slide"
            :src="props.content"
        ></iframe>
        <vue-markdown
            v-else-if="type === 'markdown'"
            class="markdown-slide"
            :options="{ linkify: true, breaks: true, html: true }"
            :source="props.content"
        ></vue-markdown>
    </div>
</template>

<style>
div.slide-container {
    /* make slides overlap one another to allow cross-fading in Vue */
    position: absolute;

    /* set size same as parent to allow "align-self" on children to work*/
    width: inherit;
    height: inherit;

    /* horizontally center-align child elements */
    justify-content: center;
    display: flex;
}

div.markdown-slide {
    /* horizontally center-align contained text */
    text-align: center !important;

    /* vertically center align div in parent */
    align-self: center;
    max-width: 1920px;
    max-height: 1080px;
    overflow: hidden;

    /* make text easier to read when on a white background */
    text-shadow: 1px 0 5px #000, 0 -1px 5px #000, 0 1px 5px #000, -1px 0 5px #000;
}

iframe.website-slide {
    /* make iframe fill all available space */
    width: inherit;
    height: inherit;
    border: none;
    overflow: hidden;
    pointer-events: none;
}


.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
