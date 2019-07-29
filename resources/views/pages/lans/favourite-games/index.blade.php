@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @if(Auth::user())
        <p class="mt-2">@lang('phrase.lan-favourite-games-user-help')</p>
        <script>
            window.addEventListener('load', function() {
                window.lanId = {{ $lan->id }}
                const app = new Vue({
                    el: '#app',
                    methods: {
                        post(game) {
                            game.lanId = lanId
                            axios.post('users/' + userId + '/favourite-games', game)
                                .then((response) => {
                                    location.reload();
                                }, (error) => {
                                    console.log('Error adding favourite game')
                                    console.log(error.response.status)
                                    console.log(error.response.data)
                                })
                        }
                    }
                });
            });
        </script>
        <div id="app">
            <games-search @selected="post"></games-search>
        </div>
    @else
        <p class="mt-2">@lang('phrase.lan-favourite-games-guest-help')</p>
    @endif
    @include('pages.lans.favourite-games.partials.list', ['lanFavourites' => $lanFavourites])
@endsection