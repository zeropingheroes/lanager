<template>
    <img :src="user.oauth_accounts[0].avatar" v-bind:title="user.username" />
</template>

<script>
    export default {
        props: ['user'],
    }
</script>