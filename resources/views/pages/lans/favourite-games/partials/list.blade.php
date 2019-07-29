<table class="table table-striped">
    <tbody>
    @foreach($lanFavourites as $lanFavourite)

        {{-- Check if the logged-in user has favourited the game --}}
        <?php $favourited = false ?>
        @foreach($lanFavourite['favourites'] as $favourite)
            @if($userFavourites->contains($favourite))
                <?php $favourited = true ?>
            @endif
        @endforeach
        <tr @if($favourited) class="table-active" @endif>
            <td>
                @include('pages.games.partials.game-logo-link',
                [
                    'name' => $lanFavourite['game']->name,
                    'url' => $lanFavourite['game']->url(),
                    'logo' => $lanFavourite['game']->logo(),
                ])
            </td>
            <td>
                {{ $lanFavourite['game']->name }}
            </td>
            <td>
                @foreach($lanFavourite['favourites'] as $favourite)
                    <a href="{{ route('users.show', $favourite->user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $favourite->user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
