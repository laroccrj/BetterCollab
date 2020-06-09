<template>
    <div>
        <img :src="albumArt">
        <span>{{title}}</span> :
        <span>{{artists}}</span> :
        <span>{{album}}</span> :
    </div>
</template>

<script>
    export default {
        name: "Song",
        props: ['song'],
        computed: {
            title: function () {
                return this.song.title
            },
            artists: function () {
                return this.song.artist
            },
            album: function () {
                return this.song.album
            },
            albumArt: function () {
                return this.song.album_art
            }
        }
    }
</script>

<style scoped>

</style>
