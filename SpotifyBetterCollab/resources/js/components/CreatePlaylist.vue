<template>
    <div>
        <input @blur="unfocusInput"
               @focusin="focusInput"
               ref="nameInput"
               type="text"
               class="input text--color-lighter-grey add"
               v-model="name">
        <span v-if="saving" class="text--color-sky-blue"><i class="fas fa-spinner fa-spin"></i></span>
        <span v-if="showSave" class="add text--color-sky-blue" @click="newPlaylist"><i class="fas fa-plus-circle"></i></span>
        <span v-else-if='!saving' @click="setFocus" class="add text--color-sky-blue"><i class="fas fa-plus"></i></span>
    </div>
</template>

<script>
    import axios from "axios"

    export default {
        name: "CreatePlaylist",
        data: function () {
            return {
                name: '',
                inputFocused: false,
                saving: false,
                placeHolderText: 'Create Playlist',
            }
        },
        mounted: function () {
           this.name = this.placeHolderText
        },
        computed: {
            showSave() {
                return !this.saving && this.name != this.placeHolderText
            }
        },
        methods: {
            setFocus () {
                this.$refs['nameInput'].focus();
                this.name = ''
            },
            focusInput() {
                this.name = ''
            },
            unfocusInput() {
                if (this.name.trim() == '')
                    this.name = this.placeHolderText
            },
            async newPlaylist() {
                this.saving = true
                await axios
                    .post('/api/playlist/new', { name: this.name })
                    .then(response => (this.$emit('newPlaylist', response.data)))
                this.saving = false
                this.name = this.placeHolderText
            },
        }
    }
</script>

<style scoped>
    .add:hover {
        cursor: pointer;
    }
    input {
        padding: 0;
    }
</style>
