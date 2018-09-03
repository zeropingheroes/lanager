@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.achievement')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.achievement')])</h1>
    {{ Breadcrumbs::render('achievements.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('achievements.store')])
    @include('pages.achievements.partials.form')
    @include('components.form.close')
@endsection