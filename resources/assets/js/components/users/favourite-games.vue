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
                axios.get('users/' + userId + '/favourite-games')
                    .then((response) => {
                        this.$data.favouriteGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting favourite games')
                    })
        }
    }
</script>