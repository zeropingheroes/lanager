<template>
    <slide v-bind:slide="currentSlide"></slide>
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
            }, 30000);
        },
        methods: {
            update() {
                console.log('Getting slides')
                axios.get('slides')
                    .then((response) => {
                        this.$data.slides = response.data.data;

                        // If there isn't already a current slide, display the first one
                        if ( this.$data.currentSlide.length === 0 ) {
                            console.log('No current slide set - displaying first slide');
                            this.displaySlide(0);
                        }
                    }, (error) => {
                        console.log('Error getting slides')
                    })
            },
            displaySlide(index) {
                this.$data.currentSlide = this.$data.slides[index];
                console.log('Displaying slide "' + this.$data.currentSlide.name + '" for ' + this.$data.currentSlide.duration + ' seconds')

                index = (index + 1) % this.$data.slides.length;
                self = this;
                setTimeout(function () {self.displaySlide(index)}, (self.$data.currentSlide.duration * 1000));
            }
        }
    }
</script>