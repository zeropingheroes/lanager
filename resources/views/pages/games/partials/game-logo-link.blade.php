    @if(!empty($logo))
        <a href="{{ $url }}" title="{{ $name }}">
            <img src="{{ $logo }}" alt="@lang('phrase.logo-for-game', ['game' => $name])">
        </a>
    @else
        <a href="{{ $url }}" class="game-logo-not-found" title="{{ $name }}">
            {{ Str::limit($name, 20) }}
        </a>
    @endif
