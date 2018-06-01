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
        <table class="table">
            <tbody>
                <event v-for="event in events"
                       v-bind:key="event.id"
                       v-bind:name="event.name"
                       v-bind:type="event.type.name"
                ></event>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                time: new moment().format("h:mma"),
                events: []
            };
        },
        mounted() {
            var self = this;
            setInterval(function () {
                self.$data.time = new moment().format("h:mma");
                self.$data.loading = true;
                axios.get("http://lanager.localhost:8000/api/events")
                    .then((response)  =>  {
                        self.$data.loading = false;
                        self.$data.events = response.data.data;
                    }, (error)  =>  {
                        self.$data.loading = false;
                    })
            }, 1000)
        }
    }
</script>