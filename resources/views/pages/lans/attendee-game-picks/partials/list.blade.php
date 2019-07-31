<table class="table table-striped">
    <tbody>
    @foreach($lanPicks as $lanPick)

        {{-- Check if the logged-in user has picked the game --}}
        <?php $picked = false ?>
        @foreach($lanPick['picks'] as $attendeePick)
            @if($userPicks->contains($attendeePick))
                <?php $picked = true ?>
            @endif
        @endforeach
        <tr @if($picked) class="table-active" @endif>
            <td>
                @include('pages.games.partials.game-logo-link',
                [
                    'name' => $lanPick['game']->name,
                    'url' => $lanPick['game']->url(),
                    'logo' => $lanPick['game']->logo(),
                ])
            </td>
            <td>
                {{ $lanPick['game']->name }}
            </td>
            <td>
                @foreach($lanPick['picks'] as $attendeePick)
                    @if($attendeePick->user->id == Auth::user()->id)
                        {{-- TODO: do this better and less hackily --}}
                        <?php $picked = $attendeePick; ?>
                    @endif
                    <a href="{{ route('users.show', $attendeePick->user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $attendeePick->user])
                    </a>
                @endforeach
            </td>
            <td>
                @if(!$picked)
{{--                    <button type="button"--}}
{{--                            class="btn btn-primary btn-sm"--}}
{{--                            title="@lang('phrase.add-game-to-lan-picks', ['game' => $lanPick['game']->name])">--}}
{{--                        <span class="oi oi-plus"></span>--}}
{{--                    </button>--}}
                @else
                    <form action="{{ route( 'lans.attendee-game-picks.destroy', ['lan' => $lan, 'attendee_game_pick' => $attendeePick]) }}"
                          method="POST"
                          class="inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit"
                                class="btn btn-primary btn-sm"
                                title="@lang('phrase.remove-game-from-lan-picks', ['game' => $lanPick['game']->name])">
                            <span class="oi oi-minus"></span>
                        </button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
