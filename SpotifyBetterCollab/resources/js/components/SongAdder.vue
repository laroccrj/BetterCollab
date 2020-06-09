<template>
    <div>
        <song-search v-model="songs"></song-search>
        <div v-if="showResults"
             v-for="song in songs"
             :key="song.id">
            <song :song="song"></song>
            <button v-on:click="addSong(song)">Add</button>
        </div>
    </div>
</template>

<script>
    import SongSearch from "./SongSearch";
    import Song from "./Song";
    export default {
        name: "SongAdder",
        components: {Song, SongSearch},
        data() {
            return {
                songs: [],
                showResults: false,
            }
        },
        watch: {
            songs: function () {
                this.showResults = true
            }
        },
        methods: {
            addSong(song) {
                this.$emit('add-song', song);
                this.showResults = false
            }
        }
    }
</script>

<style scoped>

</style>
