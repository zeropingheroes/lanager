<template>
    <span>{{ startAndEnd }}</span>
</template>

<script>
    export default {
        props: ['start', 'end'],
        computed: {
            startAndEnd() {
                var start = moment(this.start);
                var end = moment(this.end);

                // if start falls on the hour, don't display minutes
                if (start.minute() === 0) {
                    var startFormat = 'ddd ha'; // e.g. Tue 6pm
                } else {
                    var startFormat = 'ddd h:mma'; // e.g. Tue 6:30pm
                }

                // if end falls on the hour, don't display minutes
                if (end.minute() === 0) {
                    var endFormat = 'ha'; // e.g. 6pm
                } else {
                    var endFormat = 'h:mma'; // e.g. 6:30pm
                }

                // if event doesn't start and end on the same day, display the end day
                if (start.day() != end.day()) {
                    var endFormat = 'ddd ' + endFormat;
                }

                return start.format(startFormat) + ' - ' + end.format(endFormat);
            }
        }
    }
</script>