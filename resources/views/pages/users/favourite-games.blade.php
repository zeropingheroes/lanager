@extends('layouts.default')

@section('title')
    @lang('title.favourite-games')
@endsection

@section('content-header')

    <h1>@lang('title.favourite-games')</h1>

    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app'
            });
        });
    </script>
    <div id="app">
        <user-favourite-games></user-favourite-games>
    </div>

@endsection