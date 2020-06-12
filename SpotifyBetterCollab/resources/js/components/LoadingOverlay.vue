<template>
    <div v-if="loading" class='loading bg--color-black text--color-lighter-grey'>
        <div class="loading-spinner font-size--larger flex flex--width-100 flex-horizontal-center flex-vertical-center">
            <div>
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        computed: mapState({
            loading: state => state.loadingOverlay
        }),
    }
</script>

<style scoped>
    .loading {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 10;
        opacity: 0.5;
    }
    .loading-spinner {
        width: 50%;
        height: 50%;
        margin:auto;
    }
</style>
