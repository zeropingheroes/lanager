<table class="table live-games">
    @foreach($liveGames as $liveGame)
        <tr>
            <td class="game">
                <a href="{{ $liveGame['game']->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $liveGame['game']->name])">
                    <img src="{{ $liveGame['game']->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $liveGame['game']->name])">
                </a>
            </td>
            <td class="user-count">
                @lang('phrase.x-in-game', ['x' => count($liveGame['users'])])
            </td>
            <td>
                @foreach($liveGame['users'] as $user)
                    <a href="{{ route('users.show', $user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>