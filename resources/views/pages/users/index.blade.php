@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content')

    @if($currentLan)
        <h1>@lang('title.users-at-lan-name', ['lanName' => $currentLan->name])</h1>
    @else
        <h1>@lang('title.users')</h1>
    @endif

    @if(count($users))
        <table class="table table-striped">
            <tbody>
            @foreach( $users as $user )
                <tr>
                    <td>
                        @include('pages.users.partials.avatar-username', ['user' => $user])
                    </td>
                    <td>
                        @include('pages.users.partials.online-status-badge', ['user' => $user])
                    </td>
                    <td>
                        @include('pages.users.partials.private-profile-badge', ['user' => $user])
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
    @else
        <p>@lang('phrase.no-items-found', ['item' => 'users'])</p>
    @endif
@endsection