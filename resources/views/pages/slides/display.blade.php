@extends('layouts.fullscreen')

@section('title')
    @lang('title.slides')
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
        <div class="container-fluid container-no-scroll">
            <div class="row">
                <div class="col text-center">
                    <h1><a href="{{ url('/') }}">{{ request()->getHost() }}</a></h1>
                </div>
            </div>
            <slides></slides>
        </div>
    </div>
@endsection