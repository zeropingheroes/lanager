@if ($favouriteGames->isEmpty())
    @lang('phrase.username-has-not-favourited-any-games', ['username' => $user->username])
@else
    <table class="table">
    <tbody>
        @foreach($favouriteGames as $favouriteGame)
            <tr>
                <td>
                    @include('pages.games.partials.game-image-link',
                    [
                        'name' => $favouriteGame->favouriteable->name,
                        'url' => $favouriteGame->favouriteable->url(),
                        'image' => $favouriteGame->favouriteable->image(),
                    ])
                </td>
                <td>{{ $favouriteGame->favouriteable->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif