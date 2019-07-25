@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    <script>
        window.addEventListener('load', function() {
            const app = new Vue({
                el: '#app',
                methods: {
                    post(game) {
                        console.log(game)
                    }
                }
            });
        });
    </script>
    <div id="app">
        <games-search @selected="post"></games-search>
    </div>
    @include('pages.lans.favourite-games.partials.list', ['lanFavourites' => $lanFavourites])
@endsection