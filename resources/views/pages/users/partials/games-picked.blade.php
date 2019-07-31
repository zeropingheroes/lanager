@if ($gamePicks->isEmpty())
    @lang('phrase.username-has-not-picked-any-games', ['username' => $user->username])
@else
    <table class="table">
    <tbody>
        @foreach($gamePicks as $gamePick)
            <tr>
                <td>
                    @include('pages.games.partials.game-logo-link',
                    [
                        'name' => $gamePick->game->name,
                        'url' => $gamePick->game->url(),
                        'logo' => $gamePick->game->logo(),
                    ])
                </td>
                <td>{{ $gamePick->game->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif