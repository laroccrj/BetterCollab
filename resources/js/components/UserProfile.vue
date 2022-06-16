<template>
    <div class="flex flex-vertical-center">
        <div v-if="user.profile_pic">
            <img :src="user.profile_pic" style="border-radius: 50%; height:50px; width:50px;" :alt="altText">
        </div>
        <div v-else :style="colorStyle" class="font-size--large">
            {{firstInitial}}
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
        },
        computed: {
            firstInitial: function () {
                return this.user.name.charAt(0).toUpperCase()
            },
            colorStyle: function () {
                return {
                    backgroundColor: this.user.color,
                    borderRadius: '50%',
                    height: '50px',
                    width: '50px',
                    textAlign: 'center',
                    lineHeight: '50px',
                    color: '#fff'
                }
            }
        }
    }
</script>

<style scoped>
    .name {
        margin-left: 10px;
    }
</style>
