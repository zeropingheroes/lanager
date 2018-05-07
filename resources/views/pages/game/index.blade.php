@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content')

    <h1>@lang('title.live')</h1>
    @include('pages.game.partials.live', ['liveGameUsage' => $liveGameUsage])


    {{--<h1>@lang('title.recently-played')</h1>--}}
    {{--<p>Recently played games</p>--}}
    {{--<h1>@lang('title.owned')</h1>--}}
    {{--<p>Owned games</p>--}}
@endsection