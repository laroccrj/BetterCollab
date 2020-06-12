<template>
    <div>
        <song-search v-model="songs" @setLoading="setLoading"></song-search>
        <div v-if="showResults"
             v-for="song in songs"
             :key="song.id">
            <song :song="song">
                <user-profile v-if="getSongOwner(song)"
                    :user="getSongOwner(song)"
                    :show-name="false"
                    :alt-text="'Already added by ' + getSongOwner(song).name ">
                </user-profile>
                <div v-else
                     class="icon-button font-size--large flex flex-vertical-center flex-horizontal-center"
                     @click="addSong(song)">
                    <i class="fas fa-plus"></i>
                </div>
            </song>
        </div>
    </div>
</template>

<script>
    import SongSearch from "./SongSearch";
    import Song from "./Song";
    import UserProfile from "./UserProfile";
    export default {
        name: "SongAdder",
        components: {UserProfile, Song, SongSearch},
        props: [
            'playlist',
            'currentSongs'
        ],
        data() {
            return {
                songs: [],
                showResults: false,
            }
        },
        watch: {
            songs: function () {
                this.showResults = true
            }
        },
        computed: {
            playlistSongsBySpotifyId () {
                let songsBySpotifyId = {}
                this.currentSongs.forEach(song => {
                    songsBySpotifyId[song.spotify_id] = song
                })
                return songsBySpotifyId
            },
            contributorById () {
                let contributorsById = {}
                this.playlist.contributors.forEach(contributor => {
                    contributorsById[contributor.id] = contributor
                })
                return contributorsById
            },
        },
        methods: {
            addSong(song) {
                this.$emit('add-song', song);
            },
            getSongOwner(song) {
                if (this.playlistSongsBySpotifyId[song.spotify_id]) {
                    const playlistSong = this.playlistSongsBySpotifyId[song.spotify_id]
                    return this.contributorById[playlistSong.contributor_id].user
                }
            },
            setLoading(loading) {
                this.$emit('setLoading', loading)
            }
        }
    }
</script>

<style scoped>

</style>
