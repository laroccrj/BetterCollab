<template>
    <div id="container" class="flex flex-align-items-stretch flex-direction-column overflow-hidden">
        <main class="flex flex-horizontal-center text--color-lighter-grey flex--width-100 flex-align-items-stretch mobile-direction-column overflow-hidden">
            <div id="sidebar" class="flex max-width-200px flex--width-20 bg--color-black hide-mobile">
                <sidebar></sidebar>
            </div>
            <div id="mobile-sidebar" class="show-mobile">
                <mobile-sidebar></mobile-sidebar>
            </div>
            <div id="content" class="flex flex--grow-1 bg--color-dark-grey overflow-auto">
                <loading-overlay></loading-overlay>
                <div v-if="!loadingUser" class="flex flex--grow-1 overflow-auto">
                    <playlist v-if="user"></playlist>
                    <welcome v-else></welcome>
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
    import MobileSidebar from "./MobileSidebar";
    import Welcome from "./Welcome";
    export default {
        components: {Welcome, MobileSidebar, LoadingOverlay, Playlist, Sidebar, UserProfile},
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
    .overflow-auto {
        overflow: auto;
    }
    .overflow-hidden {
        overflow: hidden;
    }
    main {
        position: relative;
    }
    #content {
        position: relative;
    }
    #mobile-sidebar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 10;
        background: transparent;
    }
</style>
