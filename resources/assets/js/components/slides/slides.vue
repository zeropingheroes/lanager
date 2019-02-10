<template>
    <div class="slide-content">
        <vue-markdown :source="currentSlide.content"></vue-markdown>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                slides: [],
                currentSlide: [],
            };
        },
        created() {
            var self = this;
            self.update();
            setInterval(function () {
                self.update()
            }, 60000);
            var index = 0;
            setInterval(function () {
                index = (index + 1) % self.$data.slides.length;
                self.$data.currentSlide = self.$data.slides[index];
            }, 15000);
        },
        methods: {
            update() {
                axios.get('slides')
                    .then((response) => {
                        this.$data.slides = response.data.data;
                        if ( ! this.$data.currentSlide.length ) {
                            this.$data.currentSlide = this.$data.slides[0];
                        }
                    }, (error) => {
                        console.log('Error getting slides')
                    })
            }
        }
    }
</script>