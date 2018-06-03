<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="pull-left">Games</h1>
            </div>
        </div>
        <table class="table dashboard-table">
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
                axios.get('active-games')
                    .then((response) => {
                        this.$data.activeGames = response.data.data;
                    }, (error) => {
                        console.log('Error getting active games')
                    })
            }
        }
    }
</script>