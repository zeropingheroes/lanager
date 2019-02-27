@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content-header')
    @include('pages.events.partials.header-index', ['active' => 'schedule', 'lan' => $lan])
    {{ Breadcrumbs::render('lans.events.index', $lan) }}
@endsection

@section('content')
    <script>
        window.onload = function () {
            const app = new Vue({
                el: '#app'
            });
        }
    </script>
    <div id="app">
        <event-schedule></event-schedule>
    </div>
@endsection