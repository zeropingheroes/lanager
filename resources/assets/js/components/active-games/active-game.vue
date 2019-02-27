<template>
    <tr>
        <td class="game-image"><img :src="game.logo.medium" /></td>
        <td class="game-name">{{ game.name }}</td>
        <td class="game-user-count">{{ userCount }} In Game</td>
        <td class="game-user-list">
            <user-avatar v-for="user in users"
                              :key="user.id"
                              v-bind:user="user">
            </user-avatar>

        </td>
    </tr>
</template>

<script>
    export default {
        props: ['game', 'users'],
        computed: {
            userCount() {
                return _.size(this.users);
            },
        }
    }
</script>