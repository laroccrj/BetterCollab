<template>
    <div v-if="playlist" class="flex--width-100 flex flex-align-items-stretch flex-direction-column">
        <div class="head flex--width-100 bg--color-dark-grey padding--1">
            <div class="text--color-sky-blue font-size--larger">{{ playlist.name }}</div>
            <div v-if="isOwner" class="owner-options">
                <span :class="[showSettings ? 'underline' : '', 'tag']" @click="toggleSettings"><i class="fas fa-cog"></i> Settings</span>
                <span :class="[showShareUrl ? 'underline' : '', 'tag']" @click="toggleShareUrl"><i class="fas fa-link"></i> Share Url</span>
                <span class="tag" @click="shuffle"><i class="fas fa-random"></i> Shuffle</span>
                <span class="tag" v-if="showShareUrlCopied"><span class="text--color-sky-blue ">Copied to clipboard</span></span>
                <div v-if="showShareUrl">
                    <span class="tag">{{ collabLink }}</span>
                </div>
                <div v-if="showSettings">
                    <playlist-admin-controls v-if="isOwner"
                                             :playlist="playlist"
                                             v-on:playlist-update="updatePlaylist">
                    </playlist-admin-controls>
                </div>
            </div>
            <div class="flex flex-vertical-center">
                <div>
                    <img :src="playlist.user.profile_pic" style="border-radius: 50%; height:50px; width:50px;">
                </div>
                <div class="padding-left-10px">
                    <div class="text--color-white font-size--large">{{playlist.user.name}}</div>
                    <div>
                        <span class="tag">Owner</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabs flex flex-align-items-stretch">
            <div @click="songsTab"
                :class="[songsTabSel && !contributor ? 'tab-selected' : '', 'all-tab', 'tab', 'flex', 'flex-vertical-center', 'flex-horizontal-center', 'font-size--large']">
                <i class="fas fa-music"></i>
            </div>
            <div @click="addTab"
                :class="[addTabSel ? 'tab-selected' : '', 'all-tab', 'tab', 'flex', 'flex-vertical-center', 'flex-horizontal-center', 'font-size--large']">
                <i class="fas fa-plus"></i>
            </div>
            <div class="flex contributor-tabs">
                <div v-for="contributor in contributors"
                     @click="setContributor(contributor)"
                     :class="[isContributorSelected(contributor) ? 'tab-selected' : '', 'contributor-tab', 'tab']">
                    <user-profile :user="contributor.user"
                        :show-name="isContributorSelected(contributor)"></user-profile>
                </div>
            </div>
        </div>
        <div class="body bg--color-grey padding--1">
            <div v-if="songsTabSel">
                <song v-for="song in songs"
                      v-if="!contributor || song.contributor_id == contributor.id"
                      :key="song.id"
                      :song="song">
                    <div v-if="song.contributor_id == userContributor.id"
                         class="icon-button font-size--large flex flex-vertical-center flex-horizontal-center"
                         @click="deleteSong(song)">
                        <i class="fas fa-times"></i>
                    </div>
                    <user-profile :user="song.user" :show-name="false"></user-profile>
                </song>
            </div>
            <song-adder v-else-if="addTabSel"
                :playlist="playlist"
                :current-songs="songs"
                :song-slots-remaining="songSlotsRemaining"
                @add-song="addSong"
                @setLoading="setLoading">
            </song-adder>
        </div>
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
                playlist: null,
                showSettings: false,
                showShareUrl: false,
                showShareUrlCopied: false,
                contributor:null,
                songsTabSel: true,
                addTabSel: false,
                loading: true,
            }
        },
        mounted () {
            this.loadPlaylist()
        },
        computed: {
            collabLink: function () {
                return window.location.origin + '/collab/' + this.playlist.collab_link
            },
            userContributor: function () {
                let userContributor = null

                this.contributors.forEach(contributor => {
                    if (contributor.user_id == this.globalUser.id)
                        userContributor = contributor
                })

                return userContributor
            },
            contributors: function () {
                return this.playlist.contributors
            },
            isOwner: function () {
                return this.playlist.user_id == this.globalUser.id
            },
            allSelected: function () {
                return this.contributor == null
            },
            songs: function () {
                let songs = []

                this.contributors.forEach(contributor => {
                    contributor.songs.forEach(song => {
                        song.user = contributor.user
                        songs.push(song)
                    })
                })

                songs.sort(function(a, b){return a.priority - b.priority})
                return songs
            },
            songSlotsRemaining: function () {
                return this.playlist.song_limit - this.userContributor.songs.length
            },
            ...mapState({
                globalUser: 'user',
                playlistId : 'playlistId'
            })
        },
        watch: {
            playlistId: function (newPlaylistId) {
                this.loadPlaylist()
            }
        },
        methods: {
            async loadPlaylist() {
                this.setLoading(true)
                if (!this.playlistId) {
                    this.setLoading(false)
                    return
                }

                await axios
                    .get('/api/playlist/' + this.playlistId)
                    .then(response => (this.playlist = response.data))

                this.playlist.contributors.sort((a, b) => {
                    if (a.user.id == this.globalUser.id)
                        return -1
                    else
                        return 0
                })

                this.setLoading(false)
            },
            updatePlaylist(playlist) {
                this.playlist = playlist
            },
            toggleSettings() {
                this.showShareUrl = false
                this.showSettings = !this.showSettings
            },
            toggleShareUrl() {
                this.showSettings = false
                this.showShareUrl = !this.showShareUrl

                if (this.showShareUrl) {
                    let that = this
                    this.$copyText(this.collabLink).then(function (e) {
                        that.showShareUrlCopied = true
                        setTimeout(function () {
                            that.showShareUrlCopied = false
                        }, 3000)
                    }, function (e) {
                    })
                }
            },
            isContributorSelected (contributor) {
                return this.contributor != null && this.contributor.id == contributor.id
            },
            songsTab() {
                this.contributor = null
                this.addTabSel = false
                this.songsTabSel = true
            },
            addTab() {
                this.contributor = null
                this.addTabSel = true
                this.songsTabSel = false
            },
            setContributor(contributor) {
                this.songsTabSel = true
                this.addTabSel = false
                this.contributor = contributor
            },
            async addSong(song) {
                this.setLoading(true)
                await axios
                    .post('/api/playlist/' + this.userContributor.id + '/song/add', { spotifyId: song.spotify_id })
                    .then(response => {
                        let newSong = response.data

                        for (let i = 0; i < this.playlist.contributors.length; i++) {
                            if (this.playlist.contributors[i].id == newSong.contributor_id)
                                this.playlist.contributors[i].songs.push(newSong)
                        }

                        this.$forceUpdate()

                        return
                        this.songAddResult.show = true
                        this.songAddResult.success = 'success-message'
                        this.songAddResult.message = "Song added"
                        setTimeout(this.hideSongResult, 3000)
                    })
                    .catch(error => {
                        return
                        this.songAddResult.show = true
                        this.songAddResult.class = 'error-message'
                        this.songAddResult.message = (error.response.status == 400) ? error.response.data : "Something went wrong"
                        setTimeout(this.hideSongResult, 3000)
                    });
                this.setLoading(false)
            },
            async deleteSong (song) {
                this.setLoading(true)
                await axios
                    .post('/api/playlist/song/' + song.id + '/delete')
                    .then(response => {
                        this.playlist = response.data
                    })
                this.setLoading(false)
            },
            async shuffle () {
                this.setLoading(true)
                await axios
                    .post('/api/playlist/' + this.playlist.id + '/shuffle')
                    .then(response => {
                        this.playlist = response.data
                    })
                this.setLoading(false)
            },
            setLoading (loading) {
                this.$store.commit('setLoadingOverlay', loading)
                this.loading = loading
            }
        }
    }
</script>

<style scoped>
    .head {
        border-bottom: 5px solid #06bee1;
    }
    .body {
        height: 100%;
        overflow:scroll;
    }
    .tag {
        padding: 1px;
        color: #b3b3b3;
        text-align: center;
        vertical-align: middle;
        font-size: .8rem;
    }
    .owner-options {
        margin-top: -10px;
        margin-bottom: 10px;
    }
    .owner-options .tag:hover {
        cursor: pointer;
    }
    .underline {
        border-bottom: 1px solid #b3b3b3;
    }
    .tab {
        padding:10px;
    }
    .tab:hover {
        cursor: pointer;
        color: #F9F5FF;
    }
    .all-tab {
        width: 20%;
        max-width: 100px;
        text-align: center;
    }
    .contributor-tabs {
        width: 100%;
    }
    .contributor-tab {
    }
    .tab-selected {
        color:#F9F5FF;
        background-color: #373737;
    }
</style>
