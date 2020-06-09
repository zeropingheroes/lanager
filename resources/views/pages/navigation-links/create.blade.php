@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.navigation-link')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.navigation-link')])</h1>
    {{ Breadcrumbs::render('navigation-links.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('navigation-links.store')])
    @include('pages.navigation-links.partials.form')
    @include('components.form.close')
@endsection
