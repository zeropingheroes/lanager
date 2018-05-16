@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.event')])
@endsection

@section('content')
    <h1>@lang('title.create-item', ['item' => __('title.event')])</h1>
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('events.store')])
    @include('pages.events.partials.form')
    @include('components.form.close')
@endsection