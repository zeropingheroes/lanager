<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="pull-left">Events</h1>
            </div>
            <div class="col-md-4">
                <h1 class="pull-right">{{ time }}</h1>
            </div>
        </div>
        <table class="table dashboard-table">
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
            }, 1000)
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