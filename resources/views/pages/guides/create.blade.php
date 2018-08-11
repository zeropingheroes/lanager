@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.guide')])
@endsection

@section('content')
    <h1>@lang('title.create-item', ['item' => __('title.guide')])</h1>
    {{ Breadcrumbs::render('lans.guides.create', $lan) }}
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('lans.guides.store', ['lan' => $lan])])
    @include('pages.guides.partials.form')
    @include('components.form.close')
@endsection