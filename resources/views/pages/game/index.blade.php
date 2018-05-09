@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content')

    <h1>@lang('title.live')</h1>
    @include('pages.game.partials.live', ['liveGames' => $liveGames])

    <h1>@lang('title.recent')</h1>
    @include('pages.game.partials.recent', ['recentGames' => $recentGames])

    {{--<h1>@lang('title.owned')</h1>--}}
    {{--<p>Owned games</p>--}}
@endsection