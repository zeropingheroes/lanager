@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    {{-- Only show game pick search box for upcoming/current events--}}
    @if($lan->end > now())
        @if(Auth::user())
            <div class="alert alert-info mt-2" role="alert">
                @lang('phrase.lan-attendee-game-picks-user-help')
            </div>
            <script>
                window.addEventListener('load', function() {
                    const app = new Vue({
                        el: '#app',
                        methods: {
                            post(game) {
                                console.log(game);
                                pick = {
                                    game_provider: game.provider,
                                    game_id: game.id,
                                }
                                axios.post('lans/' + {{ $lan->id }} + '/attendee-game-picks', pick)
                                    .then((response) => {
                                        location.reload();
                                    }, (error) => {
                                        error.response.data.errors.forEach(function(error) {
                                            $("#alerts").append('<div class="alert alert-danger" role="alert">' +
                                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                '<span aria-hidden="true">&times;</span>' +
                                                '</button><span>'+error+'</span></div>')
                                        });
                                    })
                            }
                        }
                    });
                });
            </script>
            <div class="container">
                <div class="row">
                    <div class="col-md">
                        <div id="app" class="mb-3">
                            <games-search @selected="post"></games-search>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        @if(!\Request::has('mine') )
                            <a class="btn btn-secondary" href="{{ route('lans.attendee-game-picks.index', ['lan' => $lan, 'mine']) }}"
                               role="button">@lang('phrase.only-show-my-game-picks')</a>
                        @else
                            <a class="btn btn-secondary" href="{{ route('lans.attendee-game-picks.index', ['lan' => $lan]) }}"
                               role="button">@lang('phrase.show-everyones-game-picks')</a>
                        @endif
                    </div>
                </div>
            </div>
            <div id="alerts"></div>
        @else
            <div class="alert alert-info mt-2" role="alert">
                @lang('phrase.lan-attendee-game-picks-guest-help')
            </div>
        @endif
    @endif
    {{-- For past LANs with no game picks, show an info message --}}
    @if($lan->end < now() && !$lanPicks->count() )
        <div class="alert alert-info mt-2" role="alert">
            @lang('phrase.no-game-picks-to-show')
        </div>
    @else
        @include('pages.lans.attendee-game-picks.partials.list', ['lanPicks' => $lanPicks])
    @endif
@endsection