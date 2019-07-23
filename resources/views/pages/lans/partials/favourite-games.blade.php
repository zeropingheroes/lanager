<script>
    window.addEventListener('load', function() {
        window.lanId = {{ $lan->id }}
        const app = new Vue({
            el: '#app'
        });
    });
</script>
<div id="app">
    <favourite-games-page></favourite-games-page>
</div>