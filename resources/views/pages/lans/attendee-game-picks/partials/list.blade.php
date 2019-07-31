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
                    <a href="{{ route('users.show', $attendeePick->user->id) }}">
                        @include('pages.users.partials.avatar', ['user' => $attendeePick->user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
