@if ($favouriteGames->isEmpty())
    @lang('phrase.username-has-not-favourited-any-games', ['username' => $user->username])
@else
    <table class="table">
    <tbody>
        @foreach($favouriteGames as $favouriteGame)
            <tr>
                <td>@include('pages.games.partials.game-image-link', ['game' => $favouriteGame->favouriteable])</td>
                <td>{{ $favouriteGame->favouriteable->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif