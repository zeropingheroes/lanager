@if ($gamesInCommon)
    <table class="table games-owned">
        @foreach($gamesOwned as $userGame)
            <tr>
                <td class="game">
                    <a href="{{ $userGame->app->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $userGame->app->name])">
                        <img src="{{ $userGame->app->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $userGame->app->name])">
                    </a>
                </td>
                <td class="playtime-forever">
                    {{ round($userGame->playtime_forever/60, 1) }} @lang('phrase.hours-played-total')
                </td>
                <td class="playtime-two-weeks">
                    {{ round($userGame->playtime_two_weeks/60, 1) }} @lang('phrase.hours-played-two-weeks')
                </td>
            </tr>
        @endforeach
    </table>
    {{ $gamesOwned->links() }}
@endif