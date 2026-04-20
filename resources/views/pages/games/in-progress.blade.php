@extends('layouts.default')

@section('title')
    @lang('title.games-in-progress')
@endsection

@section('content-header')
    @include('pages.games.partials.navigation-dropdown', ['active' => 'in-progress'])
    {{ Breadcrumbs::render('games.in-progress') }}
@endsection

@section('content')
    <table class="table align-middle games">
        @foreach($games as $game)
            <tr>
                <td class="game">
                    @include('pages.games.partials.game-logo-link',
                    [
                        'name' => $game['game']->name,
                        'url' => $game['game']->url(),
                        'logo' => $game['game']->logo_small,
                    ])
                </td>
                <td class="user-count">
                    @lang('phrase.x-in-game', ['x' => count($game['users'])])
                </td>
                <td class="users">
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
