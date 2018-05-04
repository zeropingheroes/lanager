<table class="table">
    @foreach($games as $game)
        <tr>
            <td>
                <a href="#" title="@lang('phrase.view-game-in-steam-store')">
                    <img src="#" alt="@lang('phrase.logo-for-game', ['game' => $game['name']])">
                </a>
            </td>
            <td>
                @lang('phrase.x-in-game', ['x' => count($game['users'])])
            </td>
            <td>
                @foreach($game['users'] as $user)
                    <a href="#{{$user->id}}"><img src="#"></a>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>