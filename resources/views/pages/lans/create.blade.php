@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.lan')])
@endsection

@section('content')
    <h1>@lang('title.create-item', ['item' => __('title.lan')])</h1>
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('lans.store')])
    @include('pages.lans.partials.form')
    @include('components.form.close')
@endsection