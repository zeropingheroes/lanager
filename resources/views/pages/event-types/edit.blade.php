@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.event-type')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.event-type')])</h1>
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('event-types.update', $eventType->id)])
    @include('pages.event-types.partials.form')
    @include('components.form.close')
@endsection