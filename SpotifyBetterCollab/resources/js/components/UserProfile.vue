<template>
    <div class="flex flex-vertical-center">
        <div>
            <img :src="user.profile_pic" style="border-radius: 50%; height:50px; width:50px;" :alt="altText">
        </div>
        <div class="name" v-if="showName">
            {{user.name}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserProfile",
        props: {
            user: Object,
            showName: {
                type: Boolean,
                default: true
            },
            altText: {
                type: String,
                default: ''
            }
        }
    }
</script>

<style scoped>
    .name {
        margin-left:10px;
    }
</style>
