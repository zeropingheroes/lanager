@extends('layouts.default')

@section('title')
    {{ $user->username }}
@endsection

@section('content-header')
    <div class="profile-header">
        <div class="profile-avatar">
            @include('pages.users.partials.avatar', ['size' => 'large'])
        </div>
        <h1>
            {{ $user->username }}
        </h1>
    </div>
    <hr>
    <div class="container row">
        @foreach($authProviders as $name => $provider)
            @php
                $account = $user->accounts()->where(['provider' => $name])->first();
                $url = $account ? $account->profileUrl() : route('auth', ['provider' => $name]);
            @endphp
            <a class="col-lg-2" href="{{ $url }}">
                <div class="card text-center">
                    <div class="card-header">{{ $provider::$name }}</div>
                    <img src="/img/{{ $name }}.svg" width="100" height="100" class="card-img mr-1">
                    <div class="card-body text-center">
                    <span class="card-title">
                        @if($account)
                            {{ $account->username }}
                        @else
                            {{ __('phrase.connect-account', ['provider' => $provider::$name]) }}
                        @endif
                    </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection

@section('content')
    <hr>
    @if($user->lans)
        <h2>@lang('title.lans-attended')</h2>
        @foreach($user->lans()->orderBy('start', 'desc')->get() as $lan)
            <a href="{{ route('lans.show', $lan->id) }}">
                <span class="badge badge-primary">{{ $lan->name }}</span>
            </a>
        @endforeach
    @endif
    {{-- Show game info if the user is attending the current or most recent LAN (or there isn't a LAN) --}}
    @if( !$currentLan || $lansAttended->contains('id',$currentLan->id))
        @if($user->steamMetadata->exists && $user->steamMetadata->apps_visible == 1)
            <h2>@lang('title.games-history')</h2>
            @include('pages.users.partials.games-history', ['user' => $user])

            @if( (! Auth::user()) || ( Auth::user()->id != $user->id))
                <h2>@lang('title.games-in-common')</h2>
                @include('pages.users.partials.games-in-common', ['gamesInCommon' => $gamesInCommon])
            @endif

            <h2>@lang('title.games-library')</h2>
            @include('pages.users.partials.games-owned', ['gamesOwned' => $gamesOwned])
        @else
            <h2>@lang('title.games')</h2>
            @include('pages.users.partials.private-profile-warning', ['user' => $user])
        @endif
    @else
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.viewing-user-from-another-lan')])
    @endif
    @can('delete', $user)
        <h2>@lang('title.delete-account')</h2>
        @include('components.buttons.delete', ['item' => $user])
    @endcan

@endsection
