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
                <td class="text-right pr-0">
                    @canany(['update', 'delete'], $lanGame)
                        @include('pages.lan-games.partials.actions-dropdown', ['lanGame' => $lanGame])
                    @endcanany
                </td>
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
