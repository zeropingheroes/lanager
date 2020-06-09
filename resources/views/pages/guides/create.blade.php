@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.guide')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.guide')])</h1>
    {{ Breadcrumbs::render('lans.guides.create', $lan) }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.guides.store', ['lan' => $lan])])
    @include('pages.guides.partials.form')
    @include('components.form.close')
@endsection
