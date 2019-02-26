<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg">
                <h1 class="text-center">Games In Progress</h1>
            </div>
        </div>
        <table class="table">
            <tbody>
            <active-game v-for="activeGame in activeGames" :key="activeGame.id" v-bind="activeGame"></active-game>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                activeGames: [],
            };
        },
        created() {
            var self = this;
            self.update();
            setInterval(function () {
                self.update()
            }, 30000)
        },
        methods: {
            update() {
                axios.get('active-games?limit=5')
                    .then((response) => {
                        this.$data.activeGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting active games')
                    })
            }
        }
    }
</script>