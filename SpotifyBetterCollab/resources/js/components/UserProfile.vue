<template>
    <div class="user-profile">
        <img :src="user.profile_pic" style="border-radius: 50%; height:50px; width:50px;">
        <span>{{user.name}}</span>
    </div>
</template>

<script>
    export default {
        name: "UserProfile",
        props: ['user']
    }
</script>

<style scoped>
    .user-profile {
        display: inline-block;
    }
</style>
