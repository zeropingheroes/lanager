<script setup>
import {computed} from 'vue';
import GameBanner from '../games/game-banner.vue';
import UserAvatar from '../users/user-avatar.vue';

const props = defineProps({
    game: Object,
    users: Array
});

const userCount = computed(() => props.users.length);
</script>

<template>
    <tr>
        <td class="game-banner">
            <game-banner :game="game"></game-banner>
        </td>
        <td>{{ game.name }}</td>
        <td>
            {{ userCount }} In Game
        </td>
        <td class="users">
            <user-avatar v-for="user in users" :key="user.id" :user="user"></user-avatar>
        </td>
    </tr>
</template>

<style>
td {
    font-size: 200%;
    font-weight: bold;
    padding-left: 30px;
    padding-right: 30px;
}

td.game-banner {
    width: 184px;
}
</style>
