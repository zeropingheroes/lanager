<template>
    <slide v-bind:content="slide.content"></slide>
</template>

<script>
    export default {
        data() {
            return {
                slide: [],
            };
        },
        props: ['id', 'lan_id'],
        created() {
            var self = this;
            self.update();
            setInterval(function () {
                self.update()
            }, 30000);
        },
        methods: {
            update() {
                console.log('Getting slide')
                axios.get('lans/' + this.lan_id + ' /slides/' + this.id)
                    .then((response) => {
                        console.log('Displaying single slide');
                        this.$data.slide = response.data.data;
                    }, (error) => {
                        console.log('Error getting slide')
                    })
            }
        }
    }
</script>