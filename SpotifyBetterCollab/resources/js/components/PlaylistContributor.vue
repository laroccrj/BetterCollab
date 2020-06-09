<template>
    <div>
        <user-profile :user="user"></user-profile>
        <song-adder v-if="isGlobalUser" v-on:add-song="addSong"></song-adder>
        <div v-if="songAddResult.show" :class="[songAddResult.class]">
            {{ this.songAddResult.message }}
        </div>
        <song v-for="song in songs"
              :song="song"
              :key="song.id"></song>
    </div>
</template>

<script>
    import SongAdder from "./SongAdder";
    import Song from "./Song";
    import UserProfile from "./UserProfile";
    import { mapState } from 'vuex'
    export default {
        name: "PlaylistContributor",
        components: {UserProfile, Song, SongAdder},
        props: ['contributor'],
        data() {
            return {
                songAddResult : {
                    show: false,
                    class: 'success-message',
                    message: ''
                }
            }
        },
        computed: {
            user: function () {
                return this.contributor.user
            },
            songs: function () {
                return this.contributor.songs
            },
            isGlobalUser: function () {
                return this.user.id == this.globalUser.id
            },
            ...mapState({
                globalUser: 'user',
            })
        },
        methods: {
            addSong(song) {
                axios
                    .post('/api/playlist/' + this.contributor.id + '/song/add', { spotifyId: song.spotify_id })
                    .then(response => {
                        this.contributor.songs.push(response.data)
                        this.songAddResult.show = true
                        this.songAddResult.success = 'success-message'
                        this.songAddResult.message = "Song added"
                        setTimeout(this.hideSongResult, 3000)
                    })
                    .catch(error => {
                        this.songAddResult.show = true
                        this.songAddResult.class = 'error-message'
                        this.songAddResult.message = (error.response.status == 400) ? error.response.data : "Something went wrong"
                        setTimeout(this.hideSongResult, 3000)
                    });
            },
            hideSongResult () {
                this.songAddResult.show = false
            }
        }
    }
</script>

<style scoped>
    .error-message {
        color: red;
    }
    .success-message {
        color: green;
    }
</style>
