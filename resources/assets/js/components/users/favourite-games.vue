<template>
    <table class="table">
        <tbody>
            <favourite-game v-for="favouriteGame in favouriteGames" :key="favouriteGame.id" v-bind="favouriteGame.game"></favourite-game>
        </tbody>
    </table>
</template>

<script>
    export default {
        data() {
            return {
                favouriteGames: []
            }
        },
        mounted () {
            this.get();
        },
        methods: {
            get() {
                axios.get('users/' + userId + '/favourite-games')
                    .then((response) => {
                        this.$data.favouriteGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting favourite games')
                    })
            },
            post(game) {
                axios.post('users/' + userId + '/favourite-games', game)
                    .then((response) => {
                        this.get();
                    }, (error) => {
                        console.log('Error adding favourite game')
                    })
            },
            delete(id) {
                axios.delete('users/' + userId + '/favourite-games/' + id)
                    .then((response) => {
                        this.get();
                    }, (error) => {
                        console.log('Error deleting favourite game')
                    })
            }
        }
    }
</script>