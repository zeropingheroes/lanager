<template>
    <div class="center-contents-horizontally">
        <slide v-for="(slide, index) in slides" :key="slide.id" v-bind:content="slide.content" v-show="indexToShow == index"></slide>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                slides: [],
                indexToShow: -1,
            };
        },
        props: ['lan_id'],
        created() {
            // Get slides from API for the first time
            this.update();

            // Get slides from API periodically
            var self = this;
            setInterval(function () {
                self.update()
            }, 30000);
        },
        methods: {
            update() {
                axios.get('lans/' + this.lan_id + '/slides/')
                    .then((response) => {
                        this.$data.slides = response.data.data;
                        // If this is the first time that slides have been retrieved, begin cycling slides
                        if(this.$data.indexToShow == -1) {
                            this.cycle();
                        }
                    }, (error) => {
                        console.log('Error getting slides')
                    })
            },
            cycle() {
                this.$data.indexToShow = (this.$data.indexToShow + 1) % this.$data.slides.length;
                self = this;
                setTimeout(function () {
                    self.cycle()
                }, (self.$data.slides[self.$data.indexToShow].duration * 1000));
            },
        }
    }
</script>