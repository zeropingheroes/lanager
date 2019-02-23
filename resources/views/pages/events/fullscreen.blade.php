@extends('layouts.fullscreen')

@section('title')
    @lang('title.events')
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
        <events></events>
    </div>
@endsection