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
        @if($lansAttended->isNotEmpty())
            @foreach($lansAttended->where('published') as $lan)
                <a href="{{ route('lans.show', $lan->id) }}"><span class="badge text-bg-primary">{{ $lan->name }}</span></a>
            @endforeach
        @endif
    </div>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 border border-secondary rounded py-2 me-2">
                @include('pages.users.partials.accounts.steam', ['user' => $user])
            </div>
        </div>
    </div>
@endsection

@section('content')
    <hr>
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
    @endif
    @can('delete', $user)
        <h2>@lang('title.delete-account')</h2>
        @include('components.buttons.delete', ['item' => $user])
    @endcan

@endsection
