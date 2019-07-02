<template>
    <tr>
        <td><img :src="logo.small"></td>
        <td>{{ name }}</td>
    </tr>
</template>

<script>
    export default {
        props: ['name', 'logo'],
    }
</script>