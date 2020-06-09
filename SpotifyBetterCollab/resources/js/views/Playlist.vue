<template>
    <div v-if="playlist">
        <h1 class="text--color-sky-blue">{{ playlist.name }}</h1>
        <playlist-admin-controls v-if="isOwner"
            :playlist="playlist"
            v-on:playlist-update="updatePlaylist"></playlist-admin-controls>
        <div>Collab link: {{ collabLink }}</div>
        <playlist-contributor v-for="contributor in contributors"
                              :contributor="contributor"
                              :key="contributor.id">
        </playlist-contributor>
    </div>
</template>

<script>
    import axios from "axios"
    import { mapState } from 'vuex'
    import UserProfile from "../components/UserProfile";
    import SongSearch from "../components/SongSearch";
    import SongAdder from "../components/SongAdder";
    import Song from "../components/Song";
    import PlaylistContributor from "../components/PlaylistContributor";
    import PlaylistAdminControls from "../components/PlaylistAdminControls";

    export default {
        name: "Playlist",
        components: {PlaylistAdminControls, PlaylistContributor, Song, SongAdder, SongSearch, UserProfile},
        data () {
            return {
                playlist: null
            }
        },
        mounted () {
            this.loadPlaylist()
        },
        computed: {
            collabLink: function () {
                return window.location.origin + '/collab/' + this.playlist.collab_link
            },
            contributors: function () {
                return this.playlist.contributors
            },
            isOwner: function () {
                return this.playlist.user_id == this.globalUser.id
            },
            ...mapState({
                globalUser: 'user',
            })
        },
        methods: {
            async loadPlaylist() {
                const id = this.$route.params.id

                await axios
                    .get('/api/playlist/' + id)
                    .then(response => (this.playlist = response.data))

                this.playlist.contributors.sort((a, b) => {
                    if (a.user.id == this.globalUser.id)
                        return -1
                    else
                        return 0
                })
            },
            updatePlaylist(playlist) {
                this.playlist = playlist
            }
        }
    }
</script>

<style scoped>

</style>
