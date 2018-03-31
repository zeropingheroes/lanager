@if ( count($applications) )
    <table class="table states-current-usage">
        @foreach($applications as $application)
            <tr>
                <td class="application">
                    <a href="{{ $application['url'] }}" title="View {{ $application['name'] }} in the Steam Store">
                        <img src="{{ $application['logo_small'] }}" alt="{{ $application['name'] }}">
                    </a>
                </td>
                <td class="user-count">
                    {{ count($application['users']) }} In Game
                </td>
                <td class="user-list">
                    @foreach( $application['users'] as $user )
                        <a href="{{ URL::route('users.show', $user['id']) }}">
                            <img src="{{ $user['avatar_small']}}"> {{{ $user['username'] }}}
                        </a>
                        <br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>

@else
    <p>No game usage to show!</p>
@endif