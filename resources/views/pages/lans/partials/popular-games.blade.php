<table class="table table-striped popular-games">
    @foreach($games as $game)
        <tr>
            <td class="game">
                @include('pages.games.partials.game-logo-link',
                [
                    'name' => $game['game']->name,
                    'url' => $game['game']->url(),
                    'logo' => $game['game']->logo(),
                ])
            </td>
            <td class="playtime">
                {{ $game['playtime']->seconds(0)->cascade()->forHumans() }}
            </td>
            <td class="players">
                @foreach($game['users'] as $user)
                    <a href="{{ route('users.show', $user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>