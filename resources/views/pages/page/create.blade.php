@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.info-page')])
@endsection

@section('content')
    <h1>@lang('title.create-item', ['item' => __('title.info-page')])</h1>
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('pages.store')])
    @include('pages.page.partials.form')
    @include('components.form.close')
@endsection