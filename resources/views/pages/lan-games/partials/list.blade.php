<table class="table table-striped">
    <tbody>
    @foreach($lanGames as $lanGame)
        @can('view', $lanGame)
            <tr>
                <td>
                    {{ $lanGame->game_name }}
                </td>
                <td>
                    @foreach($lanGame->votes as $vote)
                        @include('pages.users.partials.avatar', ['user' => $vote->user])
                    @endforeach
                </td>
                @canany(['update', 'delete'], $lanGame)
                    <td class="text-right pr-0">
                        @include('pages.lan-games.partials.actions-dropdown', ['lanGame' => $lanGame])
                    </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
