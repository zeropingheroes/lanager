{{--Only show this section to guests and logged in users--}}
{{--unless their viewing their own profile--}}
@if( (! Auth::user()) || ( Auth::user()->id != $user->id))
    <h2>@lang('title.games-in-common')</h2>
    {{--Encourage guests to log in--}}
    @if (! Auth::user())
        @lang('phrase.sign-in-to-see-the-games-you-have-in-common-with-username', ['username' => $user->username])
    @elseif ($gamesInCommon->isEmpty())
        @lang('phrase.you-have-no-games-in-common-with-username', ['username' => $user->username])
    @else
        {{--Show games in common to users other than --}}
        {{--the user whose profile this is--}}
        <table class="table games-in-common">
        @foreach($gamesInCommon as $userGame)
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
        {{ $gamesInCommon->links() }}
    @endif
@endif
