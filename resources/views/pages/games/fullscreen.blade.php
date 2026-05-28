@extends('layouts.content-only')

@section('title')
    @lang('title.games')
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg">
                <h1 class="text-center">@lang('title.games')</h1>
            </div>
        </div>
        @vite(['resources/js/pages/active-games.js'])
        <div id="app">
            <active-games/>
        </div>
    </div>
@endsection
