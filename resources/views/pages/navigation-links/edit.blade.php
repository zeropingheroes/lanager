@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.navigation-link')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.navigation-link')])</h1>
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('navigation-links.update', $navigationLink->id)])
    @include('pages.navigation-links.partials.form')
    @include('components.form.close')
@endsection