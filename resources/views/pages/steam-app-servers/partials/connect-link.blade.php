@if($server->address)
    <a href="{{ $server->url() }}" title="Connect using Steam" target="_blank">
        {{ $server->address }}
    </a>
@endif
