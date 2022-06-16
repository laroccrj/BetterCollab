
<template>
    <div style="position: relative; width: 100%">
        <div style="top:10px; right: 10px; position: absolute;">
            <i class="fa fa-bars font-size--larger" aria-hidden="true" @click="toggleExpanded"></i>
        </div>
        <div v-if="expanded" style="height: 100vh" class="font-size--large text--color-lighter-grey flex--grow-1 bg--color-black">
            <div class="padding--5">
                <img src="/img/title.png">
            </div>
            <a href="/logout">
                <div class="logout text--color-white font-size--large padding--5 create-playlist padding-left-10">
                    Logout
                </div>
            </a>
            <create-playlist class="padding-left-10"
                             @newPlaylist="newPlaylist">
            </create-playlist>
            <div v-for="playlist in playlists"
                 @click="selectPlaylist(playlist.id)"
                 :class="[
                'playlist',
                isSelectPlaylist(playlist.id) ? 'playlist-selected text--color-white' : 'text--color-lighter-grey',
                'padding-left-10'
            ]">
                {{ playlist.name }}
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios"
    import { mapState } from 'vuex'
    import CreatePlaylist from "../components/CreatePlaylist";
    import UserProfile from "../components/UserProfile";

    export default {
        name: "MobileSidebar",
        components: {UserProfile, CreatePlaylist},
        data () {
            return {
                newPlaylistName : "",
                expanded: false,
            }
        },
        computed: {
            playlists: function () {
                if (!this.user) return []
                else return this.user.playlists
            },
            ...mapState([
                'user',
                'playlistId',
            ])
        },
        methods: {
            async newPlaylist(playlist) {
                this.$store.commit('addPlaylist', playlist)
                this.$store.commit('setPlaylistId', playlist.id)
                this.expanded = false
            },
            selectPlaylist(id)
            {
                this.$store.commit('setPlaylistId', id)
                this.expanded = false
            },
            isSelectPlaylist(id)
            {
                return this.playlistId == id
            },
            toggleExpanded()
            {
                this.expanded = !this.expanded
            }
        }
    }
</script>

<style scoped>
    .playlist {
        text-decoration: none;
        margin-top: 5px;
    }
    .playlist:hover {
        cursor: pointer;
        background-color: #535353;
    }
    .playlist-selected {
        box-shadow: inset 5px 0px 0px 0px #06bee1;
    }
    .create-playlist {
        border-bottom: 1px solid;
        padding-bottom: 5px;
        margin-bottom: 5px;
    }
    .logout {
        text-decoration: none;
    }
    a {
        text-decoration: none;
    }
    a:visited {color:#F9F5FF;}
    a:active {color:#F9F5FF;}
    .logout:hover {
        cursor: pointer;
        color: #F9F5FF;
    }
</style>
