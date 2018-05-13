@if ($gamesInCommon)
    <table class="table games-in-common">
        @foreach($gamesInCommon as $userGame)
            <tr>
                <td class="game">
                    <a href="{{ $userGame->app->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $userGame->app->name])">
                        <img src="{{ $userGame->app->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $userGame->app->name])">
                    </a>
                </td>
                <td class="user-count">
                    {{ round($userGame->playtime_forever/60, 1) }} @lang('phrase.hours-played')
                </td>
            </tr>
        @endforeach
    </table>
    {{ $gamesInCommon->links() }}
@endif