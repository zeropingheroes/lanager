<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg">
                <h1 class="text-center">Events</h1>
            </div>
        </div>
        <table class="table">
            <tbody>
            <event v-for="event in events" :key="event.id" v-bind="event" v-bind:now="now"></event>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                time: new moment().format("HH:mm"),
                now: new moment(),
                events: []
            };
        },
        created() {
            var self = this;
            self.update();
            setInterval(function () {
                self.update()
            }, 60000)
        },
        methods: {
            update() {
                this.$data.time = new moment().format("HH:mm");
                this.$data.now = new moment();
                axios.get('events?after=' + this.$data.now.format() + '&limit=5')
                    .then((response) => {
                        this.$data.events = response.data.data;
                    }, (error) => {
                        console.log('Error getting events')
                    })
            }
        }
    }
</script>