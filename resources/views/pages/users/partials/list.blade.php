<table class="table table-striped">
    <thead>
    <tr>
        <th>@lang('title.username')</th>
        <th>@lang('title.profile')</th>
        <th colspan="3">@lang('title.status')</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $users as $user )
        <tr>
            <td>
                @include('pages.users.partials.avatar-username', ['user' => $user])
            </td>
            <td>
                @include('pages.users.partials.private-profile-badge', ['user' => $user])
            </td>
            <td>
                @include('pages.users.partials.online-status-badge', ['user' => $user])
            </td>
            <td>
                @if($user->state)
                    @include('pages.steam-apps.partials.store-link', ['app' => $user->state->app])
                @endif
            </td>
            <td>
                @if($user->state)
                    @include('pages.steam-app-servers.partials.connect-link', ['server' => $user->state->server])
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
