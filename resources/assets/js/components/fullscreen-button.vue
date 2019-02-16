<template>
    <div id="fullscreen-button" v-show="!fullscreen">
        <span class="oi oi-fullscreen-enter" title="Enter full screen" aria-hidden="true"></span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fullscreen: false,
            };
        },
        mounted: function () {
            this.$el.addEventListener('click', this.enterFullScreen)
            document.addEventListener('webkitfullscreenchange', this.showFullScreenButtonOnExit, false);
            document.addEventListener('mozfullscreenchange', this.showFullScreenButtonOnExit, false);
            document.addEventListener('fullscreenchange', this.showFullScreenButtonOnExit, false);
            document.addEventListener('MSFullscreenChange', this.showFullScreenButtonOnExit, false);
        },
        beforeDestroy: function () {
            this.$el.removeEventListener('click', this.enterFullScreen)
        },
        methods: {
            enterFullScreen: function () {
                var elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { /* Firefox */
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE/Edge */
                    elem.msRequestFullscreen();
                }
                this.$data.fullscreen = true;
            },
            showFullScreenButtonOnExit: function () {
                if (document.webkitIsFullScreen === false ||
                    document.mozFullScreen === false ||
                    document.msFullscreenElement === null) {
                    this.$data.fullscreen = false;
                }
            }
        }
    }
</script>