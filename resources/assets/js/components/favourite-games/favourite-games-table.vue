<template>
    <div>
        <table class="table">
            <tbody>
            <favourite-games-table-row
                    v-for="favouriteGame in favouriteGames"
                    :key="favouriteGame.game.id"
                    v-bind="favouriteGame">
            </favourite-games-table-row>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                favouriteGames: []
            }
        },
        mounted () {
            this.updateList();
        },
        methods: {
            updateList() {
                axios.get('lans/' + lanId + '/favourite-games')
                    .then((response) => {
                        this.$data.favouriteGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting favourite games')
                        console.log(error.response.status)
                        console.log(error.response.data)
                    })
            },
        }
    }
</script>