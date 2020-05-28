@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.lan-game')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.lan-game')])</h1>
    {{ Breadcrumbs::render('lan-games.edit', $lanGame) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('lan-games.update', $lanGame)])
    @include('pages.lan-games.partials.form')
    @include('components.form.close')
@endsection