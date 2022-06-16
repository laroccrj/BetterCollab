import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        loadingUser: true,
        user: null,
        playlistId: null,
        loadingOverlay: false,
        songSearchCache: null,
    },
    mutations: {
        setUser (state, user) {
            state.user = user

            if (user) {
                if (user.playlists.length > 0) {
                    state.playlistId = user.playlists[0].id
                }
            }

            state.loadingUser = false
            state.loadingOverlay = false
        },
        setLoadingUser (state, loading) {
            state.loadingUser = loading
            state.loadingOverlay = loading
        },
        setPlaylistId(state, id) {
            state.playlistId = id
        },
        addPlaylist(state, playlist) {
            state.user.playlists.push(playlist)
        },
        setLoadingOverlay(state, loading) {
            state.loadingOverlay = loading
        },
        setSongSearchCache(state, songs) {
            state.songSearchCache = songs
        }
    },
    actions: {
        async getUser (store) {
            store.commit('setLoadingUser', true)
            const response = await axios.get("/api/user")
            let user = response.data
            if (!user || !user.id) user = null

            store.commit('setUser', user)
        },
    }
})

export default store
