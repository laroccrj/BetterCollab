import store from './store.js'
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueClipboard from 'vue-clipboard2'

const eventHub = new Vue()
Vue.config.devtools = true;
Vue.use(VueRouter)
Vue.use(VueClipboard)
Vue.mixin({
    data: function () {
        return {
            eventHub: eventHub
        }
    }
})

import App from './views/App'
import Index from './views/Index'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Index
        },
        {
            path: '/:id',
            name: 'home',
            component: Index
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
});
