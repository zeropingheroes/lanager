@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.whitelisted-ip-range')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.whitelisted-ip-range')])</h1>
    {{ Breadcrumbs::render('whitelisted-ip-ranges.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('whitelisted-ip-ranges.store')])
    @include('pages.whitelisted-ip-ranges.partials.form')
    @include('components.form.close')
@endsection
