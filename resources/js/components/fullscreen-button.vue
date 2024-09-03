<template>
    <transition name="fade">
        <div id="fullscreen-button" class="btn" v-show="visible">
            <span class="fa fa-solid fa-expand" title="Enter full screen" aria-hidden="true"></span>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                visible: false,
            };
        },
        mounted: function () {
            this.$el.addEventListener('click', this.toggleFullscreen)
            window.addEventListener('mousemove', this.showFullscreenButton)
        },
        beforeDestroy: function () {
            this.$el.removeEventListener('click', this.toggleFullscreen)
            window.removeEventListener('mousemove', this.showFullscreenButton)
        },
        methods: {
            toggleFullscreen: function () {
                if (
                    document.fullscreenElement ||
                    document.webkitFullscreenElement ||
                    document.msFullscreenElement
                ) {
                    this.exitFullscreen();
                } else {
                    this.enterFullscreen();
                }
            },
            enterFullscreen: function () {
                var d = document.documentElement;
                if (d.requestFullscreen) {
                    d.requestFullscreen();
                } else if (d.mozRequestFullScreen) { /* Firefox */
                    d.mozRequestFullScreen();
                } else if (d.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                    d.webkitRequestFullscreen();
                } else if (d.msRequestFullscreen) { /* IE/Edge */
                    d.msRequestFullscreen();
                }
            },
            exitFullscreen: function () {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                }
            },
            showFullscreenButton: function () {
                if (this.visible != true) {
                    this.visible = true;
                    setTimeout(() => {
                        this.visible = false;
                    }, 2000);
                }

            }
        }
    }
</script>

<style>
div#fullscreen-button {
    font-size: 400%;
    position: absolute;
    bottom: 15px;
    right: 15px;
}
</style>
