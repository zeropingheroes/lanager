<template>
    <img :src="src" v-bind:title="game.name"/>
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
            game: {
                type: Object,
            },
        },
        computed: {
            src() {
                return this.game.logo[this.size]
            }
        }
    }
</script>