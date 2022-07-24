@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.slide')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.slide')])</h1>
    {{ Breadcrumbs::render('lans.slides.create', $lan) }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.slides.store', ['lan' => $lan])])
    @include('pages.slides.partials.form')
    @include('components.form.close')
@endsection
