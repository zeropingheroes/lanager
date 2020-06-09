@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.lan')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.lan')])</h1>
    {{ Breadcrumbs::render('lans.create') }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.store')])
    @include('pages.lans.partials.form')
    @include('components.form.close')
@endsection
