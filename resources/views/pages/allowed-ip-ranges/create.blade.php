@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.allowed-ip-range')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.allowed-ip-range')])</h1>
    {{ Breadcrumbs::render('allowed-ip-ranges.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('allowed-ip-ranges.store')])
    @include('pages.allowed-ip-ranges.partials.form')
    @include('components.form.close')
@endsection
