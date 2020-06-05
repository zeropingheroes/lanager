@extends('layouts.default')

@section('title')
    @lang('title.games')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @include('pages.lan-games.partials.list', ['lanGames' => $lanGames])
    @include('pages.lan-games.partials.create-game', ['lan', $lan])
@endsection
