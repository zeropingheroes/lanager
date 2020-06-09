@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.whitelisted-ip-range')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.whitelisted-ip-range')])</h1>
    {{ Breadcrumbs::render('whitelisted-ip-ranges.edit', $whitelistedIpRange) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('whitelisted-ip-ranges.update', $whitelistedIpRange)])
    @include('pages.whitelisted-ip-ranges.partials.form')
    @include('components.form.close')
@endsection
