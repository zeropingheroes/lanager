<script>
    window.addEventListener('load', function() {
        const app = new Vue({
            el: '#app',
            methods: {
                post(game) {
                    console.log(game)
                }
            }
        });
    });
</script>
<div id="app">
    <games-search @selected="post"></games-search>
</div>