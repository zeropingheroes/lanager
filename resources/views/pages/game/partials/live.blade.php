<table class="table">
    @foreach($liveGameUsage as $gameUsage)
        {{--<pre>{{print_r($game)}}</pre>--}}
        <tr>
            <td>
                <a href="{{ $gameUsage['game']->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $gameUsage['game']->name])">
                    <img src="{{ $gameUsage['game']->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $gameUsage['game']->name])">
                </a>
            </td>
            <td>
                @lang('phrase.x-in-game', ['x' => count($gameUsage['users'])])
            </td>
            <td>
                @foreach($gameUsage['users'] as $user)
                    <a href="{{ route('users.show', $user->id) }}">
                        @include('pages.user.partials.avatar', ['user' => $user])
                    </a>
                @endforeach
            </td>
        </tr>
    @endforeach
</table>