<template>
    <div class="flex flex-space-between song">
        <div class="flex flex-vertical-center">
            <div class="art">
                <img :src="albumArt">
            </div>
            <div class="flex flex-direction-column">
                <div class="title text--color-white"><span>{{title}}</span></div>
                <div class="flex flex-vertical-center font-size--small">
                    <div class="artists">{{artists}}</div>
                    <div class="album">- {{album}}</div>
                </div>
            </div>
        </div>
        <div class="flex flex-vertical-center">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import UserProfile from "./UserProfile";
    export default {
        name: "Song",
        components: {UserProfile},
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
    .art {
        padding-right: 10px;
    }
    .song {
        padding:10px;
    }
    .song:hover {
        background-color: #212121;
    }
    .artists {
        margin-right: 5px;
    }
</style>
