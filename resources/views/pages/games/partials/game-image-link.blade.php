<a href="{{ $game->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $game->name])">
    <img src="{{ $game->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $game->name])">
</a>