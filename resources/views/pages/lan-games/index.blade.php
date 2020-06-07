@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @guest
        @include('components.alerts.alert-single', [
            'type' => 'info',
            'message' => __('phrase.log-in-to-submit-and-vote-on-games', ['lan' => $lan->name])
        ])
        <table class="table table-striped">
            <tbody>
            @foreach($lanGames as $lanGame)
                <tr>
                    <td>
                        {{ $lanGame->game_name }}
                    </td>
                    <td>
                        @foreach($lanGame->votes as $vote)
                            @include('pages.users.partials.avatar', ['user' => $vote->user])
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endguest
    @auth
        <p class="mt-2">
            @lang('phrase.which-games-would-you-like-to-play', ['lan' => $lan->name])
        </p>
        <script type="application/javascript">
            function toggleVote(lan_game_id) {
                const checkbox = document.getElementById('lan_game_' + lan_game_id + '_checkbox')
                const row = document.getElementById('lan_game_' + lan_game_id + '_row')
                const form = document.getElementById('lan_game_' + lan_game_id + '_form')
                if (checkbox.checked) {
                    checkbox.checked = false
                    row.classList.remove("bg-primary")
                } else {
                    checkbox.checked = true
                    row.classList.add("bg-primary")
                }
                form.submit()
            }
        </script>
        <table class="table table-striped">
            <tbody>
            @foreach($lanGames as $lanGame)
                @php
                    $vote = $lanGame->votes->where('user_id',Auth::user()->id)->first();
                    if($vote != null) {
                        $voted = true;
                        $route = route('lans.lan-games.votes.destroy', ['lan' => $lanGame->lan, 'lan_game' => $lanGame, 'vote' => $vote]);
                    } else {
                        $voted = false;
                        $route = route('lans.lan-games.votes.store', ['lan' => $lanGame->lan, 'lan_game' => $lanGame]);
                    }
                @endphp
                <tr class="{{ $voted ? 'bg-primary' : '' }}"
                    id="lan_game_{{ $lanGame->id }}_row">
                    <td onclick="toggleVote({{ $lanGame->id }})">
                        <form id="lan_game_{{ $lanGame->id }}_form" method="POST" action="{{ $route }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="lan_game_id" value="{{ $lanGame->id }}">
                            @if($voted)
                                {{ method_field('DELETE') }}
                            @endif
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="lan_game_{{ $lanGame->id }}_checkbox"
                                    {{ $voted ? 'checked' : '' }}
                                >
                                <label class="custom-control-label" for="lan_game_{{ $lanGame->id }}_checkbox">
                                    {{ $lanGame->game_name }}
                                </label>
                            </div>
                        </form>
                    </td>
                    <td>
                        @foreach($lanGame->votes as $vote)
                            @include('pages.users.partials.avatar', ['user' => $vote->user])
                        @endforeach
                    </td>
                    <td>
                        @can('update', $lanGame)
                            <a href="{{ route('lans.lan-games.edit', ['lan' => $lanGame->lan, 'lan_game' => $lanGame]) }}">
                                <span class="oi oi-pencil" title="Edit" aria-hidden="true"></span>
                            </a>
                        @endcan
                        @can('delete', $lanGame)
                            <form action="{{ route('lans.lan-games.destroy', ['lan' => $lanGame->lan, 'lan_game' => $lanGame]) }}"
                                  method="POST"
                                  class="confirm-deletion d-inline ml-1">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <a href="#" onclick="$(this).closest('form').submit();">
                                    <span class="oi oi-trash" title="Delete" aria-hidden="true"></span>
                                </a>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <form method="POST" action="{{ route('lans.lan-games.store', ['lan' => $lan]) }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <div class="row no-gutters">
                <div class="col">
                    <input class="form-control"
                           type="search"
                           id="game_name"
                           name="game_name"
                           placeholder="@lang('phrase.create-lan-game', ['lan' => $lan->name])">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit">
                        @lang('title.submit')
                    </button>
                </div>
            </div>
        </form>
    @endauth
@endsection
