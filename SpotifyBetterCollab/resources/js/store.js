import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        loadingUser: true,
        user: null
    },
    mutations: {
        setUser (state, user) {
            state.user = user
            state.loadingUser = false
        },
        setLoadingUser (state, loading) {
            state.loadingUser = state
        },
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
