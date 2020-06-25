<template>
    <div>
        <div class="flex flex--width-100 flex-space-between">
            <song-search v-model="songs"
                         @setLoading="setLoading"
                         ref="search"
                         class="flex--width-45 padding-left-10px"></song-search>
            <div>You have <span class="text--color-sky-blue">{{songSlotsRemaining}}</span> songs to add!</div>
        </div>
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
        <div @click="loadMore" class="loadMore">
            <i class="fas fa-plus"></i> Load more songs
        </div>
    </div>
</template>

<script>
    import SongSearch from "./SongSearch";
    import Song from "./Song";
    import UserProfile from "./UserProfile";
    import { mapState } from 'vuex'
    export default {
        name: "SongAdder",
        components: {UserProfile, Song, SongSearch},
        props: [
            'playlist',
            'currentSongs',
            'songSlotsRemaining'
        ],
        data() {
            return {
                songs: [],
            }
        },
        mounted () {
            const cacheSongs = this.$store.state.songSearchCache

            if (cacheSongs != null)
                this.songs = cacheSongs
            else
                this.$refs['search'].search()
        },
        computed: {
            showResults () {
                return this.songs.length > 0
            },
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
            ...mapState({
                songCache : 'songSearchCache'
            })
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
            },
            loadMore() {
                this.$refs['search'].loadMore()
            }
        }
    }
</script>

<style scoped>
    .loadMore {
        padding:10px;
    }
    .loadMore:hover {
        cursor: pointer;
        background-color: #212121;
    }
</style>
