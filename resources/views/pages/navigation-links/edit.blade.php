@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.navigation-link')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.navigation-link')])</h1>
    {{ Breadcrumbs::render('navigation-links.edit', $navigationLink) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('navigation-links.update', $navigationLink->id)])
    @include('pages.navigation-links.partials.form')
    @include('components.form.close')
@endsection
