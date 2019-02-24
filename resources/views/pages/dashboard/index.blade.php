@extends('layouts.fullscreen')

@section('title')
    @lang('title.dashboard')
@endsection

@section('content')
    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app'
            });
        });
    </script>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h1><u><a href="{{ url('/') }}">{{ request()->getHost() }}</a></u></h1>
                </div>
            </div>
        </div>
        <active-games></active-games>
    </div>
@endsection