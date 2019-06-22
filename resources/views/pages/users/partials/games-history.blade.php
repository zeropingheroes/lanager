@if ($gameSessions->isEmpty())
    @lang('phrase.username-has-not-played-any-games-this-lan', ['username' => $user->username])
@else
    <table class="table games-history">
        @foreach($gameSessions as $gameSession)
            <tr>
                <td class="game">
                    @include('pages.games.partials.game-image-link',
                    [
                        'name' => $gameSession->app->name,
                        'url' => $gameSession->app->steamStoreURL(),
                        'image' => $gameSession->app->image(),
                    ])
                </td>
                <td class="duration">
                    @if($gameSession->end)
                        @lang('phrase.played-for-x', [
                        'x' => $gameSession->start->second(0)->diffAsCarbonInterval($gameSession->end->second(0))->forHumans()
                        ])
                    @else
                        @lang('phrase.in-game-for-the-past-x', [
                        'x' => $gameSession->start->second(0)->diffAsCarbonInterval(now()->second(60))->forHumans()
                        ])
                    @endif
                </td>
                <td class="time">
                    @if($gameSession->end)
                        {{ $gameSession->end->format('D g:ia') }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    {{ $gameSessions->links() }}
@endif