<template>
    <tr v-bind:class="{ 'table-active': users.find(loggedInUser) }">
        <td><img :src="game.logo.small" onerror="this.style.display='none'"></td>
        <td class="game-name">{{ game.name }}</td>
        <td>{{ users.length }} attendees</td>
<!--        <td>-->
<!--            <button v-if="users.find(loggedInUser)" type="button" class="btn btn-primary btn-sm" v-on:click="unfavourite">-->
<!--                <span class="oi oi-trash"></span>-->
<!--            </button>-->
<!--        </td>-->
    </tr>
</template>

<script>
    export default {
        props: ['game', 'users'],
        methods: {
            loggedInUser(user) {
                return user.id === userId
            },
            // unfavourite() {
            // axios.delete('users/' + userId + '/favourite-games/' + this.id)
            //     .then((response) => {
            //         this.$emit('favourite-deleted')
            //     }, (error) => {
            //         console.log('Error deleting favourite game')
            //         console.log(error.response.status)
            //         console.log(error.response.data)
            //     })
            // },
        }
    }
</script>
<style lang="scss">
    @import "../../../sass/_variables.scss";
    td.game-name {
        font-size: large;
    }
</style>
