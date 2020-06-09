<template>
    <div>
        <div>Songs per user: <input v-model="songLimit" type="number"></div>
        <div><button v-on:click="saveSettings">Save</button></div>
    </div>
</template>

<script>
    import axios from "axios"

    export default {
        name: "PlaylistAdminControls",
        props: ['playlist'],
        data() {
            return {
                songLimit: 0
            }
        },
        mounted () {
            this.songLimit = this.playlist.song_limit
        },
        methods: {
            saveSettings() {
                axios
                    .post('/api/playlist/' + this.playlist.id + '/settings', { songLimit: this.songLimit })
                    .then(response => (this.$emit('playlist-update', response.data)))
            }
        }
    }
</script>

<style scoped>

</style>
