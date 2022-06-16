<template>
    <div>
        Welcome, please log in
    </div>
</template>
<style scoped>

</style>
<script>
    import { mapState } from 'vuex'
    export default {
        computed: mapState([
            'user',
        ]),
        mounted() {
            if (this.user) {
                this.$router.push('playlists')
            }
        },
        watch: {
            user: function (newUser, oldUser) {
                if (newUser) {
                    this.$router.push('playlists')
                }
            }
        },
    }
</script>
