<template>
    <div class="flex flex-vertical-center">
        <div class="flex--width-90">
            <input type="text"
                   v-model="query"
                   @keyup.enter="search"
                   class="bg--color-white text--color-dark-grey flex--width-100 padding--1"
                   placeholder="Search for Songs, Artists, or Albums">
        </div>
        <div class="search-button font-size--large flex flex-vertical-center flex-horizontal-center"
             @click="search">
            <i class="fas fa-search"></i>
        </div>
    </div>
</template>

<script>
    import axios from "axios"
    export default {
        name: "SongSearch",
        props: ['value'],
        data() {
            return {
                query: '',
                items: []
            }
        },
        methods: {
            async search () {
                this.setLoading(true)
                await axios
                    .get('/api/song/search?q=' + this.query)
                    .then(response => (this.items = response.data))

                this.$emit('input', this.items);
                this.$store.commit('setSongSearchCache', this.items)
                this.setLoading(false)
            },
            setLoading(loading) {
                this.$emit('setLoading', loading)
            }
        }
    }
</script>

<style scoped>
    input {
        border-radius: 15px;
        min-width: 100px;
    }
    .search-button {
        margin-left: 10px;
        width: 50px;
    }
    .search-button:hover {
        width: 50px;
        cursor: pointer;
        color: #F9F5FF;
    }
</style>
