import store from './store.js'
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.config.devtools = true;
Vue.use(VueRouter)

import App from './views/App'
import Index from './views/Index'
import Playlists from './views/Playlists'
import Playlist from './views/Playlist'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Index
        },
        {
            path: '/playlists',
            name: 'playlists',
            component: Playlists
        },
        {
            path: '/playlist/:id',
            name: 'playlist',
            component: Playlist
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
});
