@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @vite(['resources/js/pages/schedule.js'])
    <div id="app">
        <div id="schedule">
            <event-schedule></event-schedule>
        </div>
    </div>
@endsection
