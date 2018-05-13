@extends('layouts.default')

@section('title')
    {{ $user->username }}
@endsection

@section('content')

    @include('pages.users.partials.private-profile-warning', ['user' => $user])

    <div class="profile-header">
        <div class="profile-avatar">
            @include('pages.users.partials.avatar', ['size' => 'large'])
        </div>
        <h1>
            {{ $user->username }}
        </h1>
    </div>
    <hr>
    <h2>@lang('title.linked-accounts')</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 border border-secondary rounded py-2 mr-2">
                @include('pages.users.partials.accounts.steam', ['user' => $user])
            </div>
        </div>
    </div>
    <hr>
    @if ($gamesInCommon)
        <h2>@lang('title.games-in-common')</h2>
        <table class="table games-in-common">
            @foreach($gamesInCommon as $userGame)
                <tr>
                    <td class="game">
                        <a href="{{ $userGame->app->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $userGame->app->name])">
                            <img src="{{ $userGame->app->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $userGame->app->name])">
                        </a>
                    </td>
                    <td class="user-count">
                        {{ round($userGame->playtime_forever/60, 1) }} @lang('phrase.hours-played')
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $gamesInCommon->links() }}
    @endif
    {{--<h2>@lang('title.recent')</h2>--}}
    {{--<h2>@lang('title.owned')</h2>--}}

@endsection
