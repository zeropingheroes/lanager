@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.lan')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.lan')])</h1>
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('lans.update', $lan->id)])
    @include('pages.lans.partials.form')
    @include('components.form.close')
@endsection