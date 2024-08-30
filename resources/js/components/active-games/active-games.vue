<script setup>
import {ref, onMounted, onUnmounted} from 'vue'
import axios from 'axios'
import ActiveGame from './active-game.vue'

const activeGames = ref([])

const update = () => {
    axios.get('active-games?limit=5')
        .then((response) => {
            activeGames.value = response.data.data
        })
        .catch((error) => {
            console.log('Error getting active games')
        })
}

let intervalId

onMounted(() => {
    update()
    intervalId = setInterval(update, 30000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <table>
            <tbody>
            <active-game
                v-for="activeGame in activeGames"
                :key="activeGame.id"
                v-bind="activeGame"
            ></active-game>
            </tbody>
        </table>
</template>

<style>
table {
    width: 100%;
}
</style>
