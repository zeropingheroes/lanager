<script type="application/javascript">
    function toggleVote(lan_game_id) {
        document.getElementById('lan_game_' + lan_game_id + '_checkbox').checked = !document.getElementById('lan_game_' + lan_game_id + '_checkbox').checked
    }
</script>
<table class="table table-striped">
    <tbody>
    @foreach($lanGames as $lanGame)
        @can('view', $lanGame)
            @php
                if (Auth::user()) {
                    $voted = $lanGame->votes->where('user_id',Auth::user()->id)->count();
                } else {
                    $voted = false;
                }
            @endphp
            <tr class="{{ $voted ? 'bg-primary' : '' }}" onclick="toggleVote({{ $lanGame->id }})">
                <td>
                    <form>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="lan_game_{{ $lanGame->id }}_checkbox"
                                {{ $voted ? 'checked' : '' }}
                            >
                            <label class="custom-control-label" for="lan_game_{{ $lanGame->id }}_checkbox">
                                {{ $lanGame->game_name }}
                            </label>
                        </div>
                    </form>
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
