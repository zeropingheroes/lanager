<template>
    <span :class="className">{{ statusName }}</span>
</template>

<script>
    export default {
        props: ['status'],
        computed: {
            statusName() {
                switch(this.status) {
                    case 'past':
                        return 'Ended'
                    case 'present':
                        return 'Now'
                    case 'future':
                        return 'Next'
                    default:
                        return 'Unknown'
                }
            },
            className() {
                switch(this.status) {
                    case 'past':
                        return 'badge badge-danger'
                    case 'present':
                        return 'badge badge-primary'
                    case 'future':
                        return 'badge badge-info'
                    default:
                        return 'badge badge-secondary'
                }
            }
        }
    }
</script>