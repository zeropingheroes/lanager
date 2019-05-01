@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.venue')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.venue')])</h1>
    {{ Breadcrumbs::render('venues.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('venues.store')])
    @include('pages.venues.partials.form')
    @include('components.form.close')
@endsection