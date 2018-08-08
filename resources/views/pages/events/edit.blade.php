@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.event')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.event')])</h1>
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('lans.events.update', ['lan' => $lan, 'event' => $event])])
    @include('pages.events.partials.form')
    @include('components.form.close')
@endsection