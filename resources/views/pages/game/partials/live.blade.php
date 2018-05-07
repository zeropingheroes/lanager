<table class="table">
    @foreach($games as $game)
        <tr>
            <td>
                <a href="{{ $game->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $game->name])">
                    <img src="{{ $game->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $game->name])">
                </a>
            </td>
            <td>
                @lang('phrase.x-in-game', ['x' => count($game['users'])])
            </td>
            <td>
                @foreach($game['users'] as $user)
                    <a href="{{ route('users.show', $user->id) }}">
                        @include('pages.user.partials.avatar', ['user' => $user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>