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
                @php($activeSession = $user->steamAppSessions()->active()->first())
                @if($activeSession)
                    @include('pages.steam-apps.partials.store-link', ['app' => $activeSession->app])
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
