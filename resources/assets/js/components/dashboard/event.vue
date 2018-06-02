<template>
    <tr>
        <td>
            <status v-bind:status="status()"></status>
        </td>
        <td>{{ name }}</td>
        <td>{{ type.name }}</td>
        <td>
            <relative-time v-bind:status="status()"
                           v-bind:start="start"
                           v-bind:end="end"
                           v-bind:now="now">
            </relative-time>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['name', 'type', 'start', 'end', 'now'],
        methods: {
            status() {
                if (moment(this.start).isBefore(this.now) && moment(this.end).isAfter(this.now)) {
                    return 'present';
                }
                else if (moment(this.start).isAfter(this.now) && moment(this.end).isAfter(this.now)) {
                    return 'future';
                } else {
                    return 'past';
                }
            },
        },
        computed: {
            timer() {
                return moment(this.start).fromNow()
            },
        }
    }
</script>