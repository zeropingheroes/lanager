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
            <tr>
                <td class="event-status"><span class="badge badge-primary">Now</span></td>
                <td class="event-name"><a href="http://lanager.localhost:8080/events/1">Testing 1234</a></td>
                <td class="event-type">Ceremony</td>
                <td class="event-timer">Ending in 16 minutes</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                time: 0,
            };
        },
        mounted() {
            var self = this;
            setInterval(function () {
                self.$data.time = new moment().format("h:mma")
            }, 1000)
        }
    }
</script>