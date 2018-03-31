@if ( ! count($state) )
    <p>Status unknown!</p>
@else

    <h3>@include('users.partials.status-label', ['state' => $state])</h3>

    @if ( isset( $state->application->steam_app_id) )

        <table class="table user-status">
            <thead>
            <tr>
                <th colspan="2">Game</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="application-logo">
                    @include('applications.partials.button', ['application' => $state->application])
                </td>
                <td class="application-name">
                    {{{ $state->application->name }}}
                </td>
            </tr>
            </tbody>
        </table>
    @endif
@endif
