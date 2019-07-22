<template>
    <div>
        <game-search-suggest @favourite-added="updateList"></game-search-suggest>
        <table class="table">
            <tbody>
            <favourite-game v-for="favouriteGame in favouriteGames"
                            :key="favouriteGame.id"
                            v-bind="favouriteGame"
                            @favourite-deleted="updateList">
            </favourite-game>
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
                axios.get('users/' + userId + '/favourite-games')
                    .then((response) => {
                        this.$data.favouriteGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting favourite games')
                        console.log(error.response.status)
                        console.log(error.response.data)
                    })
            },
            delete(id) {
                axios.delete('users/' + userId + '/favourite-games/' + id)
                    .then((response) => {
                        this.get();
                    }, (error) => {
                        console.log('Error deleting favourite game')
                        console.log(error.response.status)
                        console.log(error.response.data)
                    })
            },
        }
    }
</script>