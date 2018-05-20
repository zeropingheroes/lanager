@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content')

    <h1>@lang('title.users')</h1>
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
                    @include('pages.steam-apps.partials.store-link', ['app' => $user->state->app])
                    </td>
                    <td>
                    @include('pages.steam-app-servers.partials.connect-link', ['server' => $user->state->server])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>@lang('phrase.no-items-found', ['item' => 'users'])</p>
    @endif
    @if(request('historic'))
        <a href="{{ request()->fullUrlWithQuery(['historic' => false]) }}" class="btn btn-primary" role="button" aria-pressed="true">@lang('title.hide-historic-users')</a>
    @else
        <a href="{{ request()->fullUrlWithQuery(['historic' => true]) }}" class="btn btn-primary" role="button" aria-pressed="true">@lang('title.show-historic-users')</a>
    @endif
@endsection