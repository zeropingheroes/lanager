@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.event')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.event')])</h1>
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.events.store', ['lan' => $lan])])
    @include('pages.events.partials.form')
    @include('components.form.close')
@endsection