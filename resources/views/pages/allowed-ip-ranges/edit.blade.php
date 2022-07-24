@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.allowed-ip-range')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.allowed-ip-range')])</h1>
    {{ Breadcrumbs::render('allowed-ip-ranges.edit', $allowedIpRange) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('allowed-ip-ranges.update', $allowedIpRange)])
    @include('pages.allowed-ip-ranges.partials.form')
    @include('components.form.close')
@endsection
