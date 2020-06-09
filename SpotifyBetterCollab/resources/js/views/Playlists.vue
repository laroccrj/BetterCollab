
<template>
    <div>
        <h1>Playlists</h1>
        <h2>New Playlist</h2>
        <input type="text" v-model="newPlaylistName">
        <button v-on:click="newPlaylist">Create</button>
        <div v-for="playlist in playlists">
            <router-link :to="'playlist/' + playlist.id">{{playlist.name}}</router-link>
        </div>
    </div>
</template>

<script>
    import axios from "axios"

    export default {
        name: "Playlists",
        data () {
            return {
                newPlaylistName : "",
                playlists: {}
            }
        },
        mounted () {
            this.loadPlaylists()
        },
        methods: {
            async newPlaylist() {
                await axios
                    .post('/api/playlist/new', { name: this.newPlaylistName })
                    .then(response => (this.playlists.push(response.data)))
            },
            async loadPlaylists() {
                axios
                    .get('/api/playlist')
                    .then(response => (this.playlists = response.data))
            }
        }
    }
</script>

<style scoped>

</style>
