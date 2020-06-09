<template>
    <div>
        <input type="text" v-model="query">
        <button v-on:click="search">Search</button>
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
                await axios
                    .get('/api/song/search?q=' + this.query)
                    .then(response => (this.items = response.data))

                this.$emit('input', this.items);
            }
        }
    }
</script>

<style scoped>

</style>
