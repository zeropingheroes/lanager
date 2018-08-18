@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.event-type')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.event-type')])</h1>
    {{ Breadcrumbs::render('event-types.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('event-types.store')])
    @include('pages.event-types.partials.form')
    @include('components.form.close')
@endsection