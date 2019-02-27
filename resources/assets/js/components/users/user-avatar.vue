<template>
    <img :src="src" v-bind:title="user.username"/>
</template>

<script>
    export default {
        props: {
            size: {
                type: String,
                validator: function (value) {
                    return ['small', 'medium', 'large'].indexOf(value) !== -1
                },
                default: 'medium',
            },
            user: {
                type: Object,
            },
        },
        computed: {
            src() {
                return this.user.oauth_accounts[0].avatar[this.size]
            }
        }
    }
</script>