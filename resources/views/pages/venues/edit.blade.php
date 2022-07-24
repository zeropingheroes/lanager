@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.venue')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.venue')])</h1>
    {{ Breadcrumbs::render('venues.edit', $venue) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('venues.update', $venue)])
    @include('pages.venues.partials.form')
    @include('components.form.close')
@endsection
