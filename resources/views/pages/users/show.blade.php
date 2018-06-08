@extends('layouts.default')

@section('title')
    {{ $user->username }}
@endsection

@section('content')

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
    {{-- Show game info if the user is attending the current or most recent LAN (or there isn't a LAN) --}}
    @if( !$currentLan || $lansAttended->contains('id',$currentLan->id))
        @if($user->SteamMetadata && $user->SteamMetadata->apps_visible == 1)
            @include('pages.users.partials.games-in-common', ['gamesInCommon' => $gamesInCommon])

            <h2>@lang('title.games')</h2>
            @include('pages.users.partials.games-owned', ['gamesOwned' => $gamesOwned])
        @else
            <h2>@lang('title.games')</h2>
            @include('pages.users.partials.private-profile-warning', ['user' => $user])
        @endif
    @else
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.viewing-user-from-another-lan')])
    @endif
    @if($user->lans)
        <h2>@lang('title.lans-attended')</h2>
        @foreach($user->lans()->orderBy('start', 'desc')->get() as $lan)
            <a href="{{ route('lans.show', $lan->id) }}">
                <span class="badge badge-primary">{{ $lan->name }}</span>
            </a>
        @endforeach
    @endif

@endsection
