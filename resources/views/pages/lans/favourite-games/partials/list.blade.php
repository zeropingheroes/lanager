<table class="table table-striped">
    <tbody>
    @foreach($favourites as $favourite)
        <tr>
            <td>
                @include('pages.games.partials.game-logo-link',
                [
                    'name' => $favourite['game']->name,
                    'url' => $favourite['game']->url(),
                    'logo' => $favourite['game']->logo(),
                ])
            </td>
            <td>
                {{ $favourite['game']->name }}
            </td>
            <td>
                @foreach($favourite['favourites'] as $favourite)
                    <a href="{{ route('users.show', $favourite->user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $favourite->user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
