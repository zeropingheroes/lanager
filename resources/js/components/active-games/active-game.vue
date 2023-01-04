<template>
    <tr>
        <td>
            <game-banner v-bind:game="game"></game-banner>
        </td>
        <td>{{ game.name }}</td>
        <td>{{ userCount }} In Game</td>
        <td>
            <user-avatar v-for="user in users" :key="user.id" v-bind:user="user"></user-avatar>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['game', 'users'],
        computed: {
            userCount() {
                return this.users.size;
            },
        }
    }
</script>
