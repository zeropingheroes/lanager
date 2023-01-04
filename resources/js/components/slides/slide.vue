<template>
    <div class="slide-container">
        <iframe
            class="website-slide"
            v-if="type === 'website'"
            :src="content">
        </iframe>
        <vue-markdown
            class="markdown-slide"
            v-else-if="type === 'markdown'"
            :options="{linkify: true, breaks: true}"
            :source="content">
        </vue-markdown>
    </div>
</template>

<script>
export default {
    props: ['content'],
    computed: {
        type() {
            if (validator.isURL(this.content, {require_tld: false})) {
                return 'website'
            } else {
                return 'markdown'
            }
        }
    }
}
</script>
