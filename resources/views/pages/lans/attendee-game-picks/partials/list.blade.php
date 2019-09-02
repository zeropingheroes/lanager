<table class="table table-striped">
    <tbody>
    @foreach($games as $game)
        @if(\Request::has('mine') && Auth::user() && !isset($game['auth_user_pick']))
            {{-- If URL has ?mine then Hide rows the logged in user didn't pick --}}
            @continue
        @endif
        <tr @if(isset($game['auth_user_pick'])) class="table-active" @endif>
            <td>
                @include('pages.games.partials.game-logo-link',
                [
                    'name' => $game['name'],
                    'url' => $game['url'],
                    'logo' => $game['logo'],
                ])
            </td>
            <td>
                {{ $game['name'] }}
            </td>
            <td>
                @foreach($game['picks'] as $pick)
                    <a href="{{ route('users.show', $pick->user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $pick->user])
                    </a>
                @endforeach
            </td>
            @if(Auth::user() && $lan->end > now())
                <td>
                    @if(!isset($game['auth_user_pick']))
                        <form action="{{ route('lans.attendee-game-picks.store', $lan) }}" method="POST" class="inline">
                            {{ csrf_field() }}
                            <input type="hidden" name="game_id" value="{{ $game['id'] }}">
                            <input type="hidden" name="game_provider" value="{{ $game['provider'] }}">
                            <button type="submit"
                                    class="btn btn-primary btn-sm"
                                    title="@lang('phrase.add-game-to-lan-picks', ['game' => $game['name']])">
                                <span class="oi oi-plus"></span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('lans.attendee-game-picks.destroy',
                            [
                                'lan' => $lan,
                                'attendee_game_pick' => $game['auth_user_pick']
                            ]) }}"
                              method="POST"
                              class="inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit"
                                    class="btn btn-primary btn-sm"
                                    title="@lang('phrase.remove-game-from-lan-picks', ['game' => $game['name']])">
                                <span class="oi oi-minus"></span>
                            </button>
                        </form>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
