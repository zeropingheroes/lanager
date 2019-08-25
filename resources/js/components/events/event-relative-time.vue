<template>
    <span>{{ relativeTimeText }}</span>
</template>

<script>
    export default {
        props: ['status', 'start', 'end', 'now'],
        data() {
            return {
                relativeTimeText: '',
            };
        },
        created() {
            this.updateRelativeTimeText();
        },
        watch: {
            now: function (newTime, oldTime) {
                this.updateRelativeTimeText();
            }
        },
        methods: {
            updateRelativeTimeText() {
                switch(this.status) {
                    case 'past':
                        this.relativeTimeText = 'Ended ' + moment(this.end).fromNow();
                        break;
                    case 'present':
                        this.relativeTimeText = 'Started ' + moment(this.start).fromNow();
                        break;
                    case 'future':
                        this.relativeTimeText = 'Starting ' + moment(this.start).fromNow();
                        break;
                    default:
                        this.relativeTimeText = 'Unknown'
                }
            }
        }
    }
</script>