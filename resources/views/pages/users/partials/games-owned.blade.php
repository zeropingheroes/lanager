@if ($gamesOwned->isEmpty())
    @lang('phrase.username-does-not-own-any-games', ['username' => $user->username])
@else
    <table class="table games-owned">
        @foreach($gamesOwned as $userGame)
            <tr>
                <td class="game">
                    @include('pages.games.partials.game-logo-link',
                    [
                        'name' => $userGame->app->name,
                        'url' => $userGame->app->url(),
                        'logo' => $userGame->app->logo(),
                    ])
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