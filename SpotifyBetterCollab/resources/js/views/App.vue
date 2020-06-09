<template>
    <div>
        <div>
            <div class="flex flex--align-center bg--color-sky-blue  text--color-black header">
                <div class="title">
                    List Together For Spotify
                </div>
                <div class="flex--grow-1 ">

                </div>
                <div v-if="!user">
                    <a href="/login">
                        <div class="login-button bg--color-black flex flex--align-center">
                            <img src="img/spotify_icon.png">
                            <span class="login-button-text">Login</span>
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
        <main>
            <div v-if="loadingUser">Loading...</div>
            <router-view v-else></router-view>
        </main>
    </div>
</template>
<script>
    import { mapState } from 'vuex'
    import UserProfile from "../components/UserProfile";
    export default {
        components: {UserProfile},
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
    .profile-pic {
        border-radius: 50%;
        height:50px;
        width:50px;
        display: block;
    }
    .title {
        font-size: 2rem;
        font-weight: bold;
    }
    .header {
        padding: 1%;
    }
    .header div {
        text-align: center;
        vertical-align: middle;
    }
    .login-button img {
        height: 50px;
        width: 50px;
    }
    .login-button {
        color: white;
        padding: 5%;
        width: 150px;
    }
    .login-button-text {
        margin-left: 25px;
        text-align: center;
        vertical-align: middle;
    }
    main {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
