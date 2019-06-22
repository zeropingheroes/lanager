@extends('layouts.default')

@section('title')
    @lang('title.recently-played-games')
@endsection

@section('content-header')
    @include('pages.games.partials.navigation-dropdown', ['active' => 'recent'])
    {{ Breadcrumbs::render('games.recent') }}
@endsection

@section('content')
    <table class="table recent-games">
        @foreach($games as $game)
            <tr>
                <td class="game">
                    @include('pages.games.partials.game-image-link',
                        [
                            'name' => $game['game']->name,
                            'url' => $game['game']->url(),
                            'image' => $game['game']->image(),
                        ])
                </td>
                <td class="user-count">
                    @lang('phrase.x-played-recently', ['x' => count($game['users'])])
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