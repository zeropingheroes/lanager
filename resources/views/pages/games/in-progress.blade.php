@extends('layouts.default')

@section('title')
    @lang('title.games-in-progress')
@endsection

@section('content')

    <h1>@lang('title.games-in-progress')</h1>

    {{ Breadcrumbs::render('games.in-progress') }}

    <table class="table live-games">
        @foreach($games as $game)
            <tr>
                <td class="game">
                    <a href="{{ $game['game']->steamStoreURL() }}" title="@lang('phrase.view-game-in-steam-store', ['game' => $game['game']->name])">
                        <img src="{{ $game['game']->image() }}" alt="@lang('phrase.logo-for-game', ['game' => $game['game']->name])">
                    </a>
                </td>
                <td class="user-count">
                    @lang('phrase.x-in-game', ['x' => count($game['users'])])
                </td>
                <td>
                    @foreach($game['users'] as $user)
                        <a href="{{ route('users.show', $user->id) }}">
                            @include('pages.users.partials.avatar', ['user' => $user])
                        </a>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>
@endsection