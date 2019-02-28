<template>
    <transition-group name="fade" class="inherit-size" tag="div">
        <slide v-for="(slide, index) in slides" :key="slide.id" v-bind:content="slide.content" v-show="indexToShow == index"></slide>
    </transition-group>
</template>

<script>
    export default {
        data() {
            return {
                slides: [],
                indexToShow: null,
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

                        // If slides were retrieved and no slide is currently being displayed
                        if (this.$data.slides.length != 0 && this.$data.indexToShow == null) {
                            // Begin cycling slides
                            this.cycle();
                        }
                    }, (error) => {
                        console.log('Error getting slides')
                    })
            },
            cycle() {
                // If there are no slides to show
                if (this.$data.slides.length == 0) {

                    // Reset the index
                    this.$data.indexToShow = null;

                // If there are slides to show
                } else {
                    // If no slide is currently showing
                    if (this.$data.indexToShow == null) {
                        // Display the first slide
                        this.$data.indexToShow = 0;

                    // If a slide is currently being shown
                    } else {
                        // Display the next slide (looping back to the first slide if needed)
                        this.$data.indexToShow = (this.$data.indexToShow + 1) % this.$data.slides.length;
                    }
                    // Call this function again after the current slide has been shown for its duration
                    self = this;
                    setTimeout(function () {
                        self.cycle()
                    }, (self.$data.slides[self.$data.indexToShow].duration * 1000));
                }
            },
        }
    }
</script>