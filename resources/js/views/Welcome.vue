<template>
    <div class="padding--1 flex--width-100">
        <div class="font-size--larger text--color-sky-blue">
            Welcome To AUXControl
        </div>
        <div class="font-size--large">
            Don't fight over the aux cord again!
        </div>
        <div class="font-size--large text--color-sky-blue">
            To Get Started:
        </div>
        <a href="/login">
            <div class="spotify font-size--large">
                Sign In Through Spotify
            </div>
        </a>
    </div>
</template>
<script>
    export default {
        name: "Welcome",
        methods: {
        }
    }
</script>
<style scoped>
    .spotify {
        background-color: #1DB954;
        color: #fff;
        border-radius: 15px;
        text-align: center;
        max-width: 300px;
    }
    a {
         text-decoration: none;
     }
    a:visited {color:#fff;}
    a:active {color:#fff;}
</style>
