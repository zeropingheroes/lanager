{{-- TODO: fix footer positioning --}}
<footer class="footer bg-dark">
    <div class="container">
        <span class="text-muted">
            <a href="https://github.com/zeropingheroes/lanager" target="_blank">
                {{ config('app.name') }}
            </a>
            @lang('phrase.is-copyright') {{ date('Y') }}
            <a href="http://www.zeropingheroes.co.uk/" target="_blank">
                Zero Ping Heroes Ltd
            </a>
            @lang('phrase.and-licensed-under')
            <a href="https://github.com/zeropingheroes/lanager/blob/master/LICENSE.txt" target="_blank">
                AGPLv3
            </a>
        </span>
        <span class="float-right">
            <a href="https://store.steampowered.com/" target="_blank">@lang('phrase.powered-by-steam')</a>
        </span>
    </div>
</footer>
</body>
</html>



