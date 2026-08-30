@extends('layouts.content-only')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg">
                <h1 class="text-center" style="font-size: 600%">@lang('title.events')</h1>
            </div>
        </div>
        @vite(['resources/js/pages/events.js'])
        <div id="app">
            <events v-bind:lan_id="{{ $lan->id }}"/>
        </div>
    </div>
@endsection
