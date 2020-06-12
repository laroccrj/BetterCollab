<template>
    <div id="container" class="flex flex-align-items-stretch flex-direction-column overflow-hidden">
        <div>
            <div class="flex flex-space-between flex-vertical-center bg--color-sky-blue text--color-black padding--1">
                <div class="title">
                    List Together For Spotify
                </div>
                <div v-if="!user">
                    <a href="/login">
                        <div class="login-button bg--color-black flex">
                            <div class="flex">
                                <img src="img/spotify_icon.png">
                            </div>
                            <div class="login-button-text flex-grow-1">
                                Login
                            </div>
                        </div>
                    </a>
                </div>
                <div v-else>
                    <div class="nav-menu flex flex--align-center">
                        <div class="flex--width-35">
                            Playlists
                        </div>
                        <div class="flex--width-35">
                            Logout
                        </div>
                        <div class="flex--width-35">
                            <img :src="user.profile_pic" class="profile-pic">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main class="flex flex-horizontal-center text--color-lighter-grey flex--width-100 flex-align-items-stretch overflow-hidden">
            <loading-overlay></loading-overlay>
            <div v-if="loadingUser">Loading...</div>
            <div v-else class="flex flex-horizontal-center text--color-lighter-grey flex--width-100">
                <div id="sidebar" class="flex flex--width-20  bg--color-black">
                    <sidebar></sidebar>
                </div>
                <div id="content" class="flex flex--grow-1 bg--color-dark-grey overflow-auto">
                    <playlist></playlist>
                </div>
            </div>
        </main>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import UserProfile from "../components/UserProfile";
    import Sidebar from "./Sidebar";
    import Playlist from "./Playlist";
    import LoadingOverlay from "../components/LoadingOverlay";
    export default {
        components: {LoadingOverlay, Playlist, Sidebar, UserProfile},
        computed: mapState([
            'user',
            'loadingUser',
        ]),
        mounted() {
            this.$store.dispatch('getUser')
        },
        watch: {
            loadingUser: function (loading) {
                if (!loading && !this.user && this.$router.currentRoute.path !== '/') {
                    this.$router.push('/')
                }
            }
        },
    }
</script>
<style scoped>
    #container, main {
        height: 100%;
    }
    .profile-pic {
        border-radius: 50%;
        height:50px;
        width:50px;
        display: block;
    }
    .title {
        font-size: 2rem;
        font-weight: bold;
        margin: auto 0;
    }
    .header div {
        text-align: center;
        vertical-align: middle;
    }
    .login-button img {
        height: 30px;
        width: 30px;
    }
    .login-button {
        color: white;
        padding: 10px;
        border-radius: 45px;
    }
    .login-button-text {
        margin: auto 0;
        text-align: center;
        font-size: 1.1rem;
        padding: 0 10px;
        font-weight: bold;
    }
    #sidebar {
        max-width: 200px;
    }
    .overflow-auto {
        overflow: auto;
    }
    .overflow-hidden {
        overflow: hidden;
    }
    main {
        position: relative;
    }
</style>
