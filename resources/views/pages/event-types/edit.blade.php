@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.event-type')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.event-type')])</h1>
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('event-types.update', $eventType->id)])
    @include('pages.event-types.partials.form')
    @include('components.form.close')
@endsection