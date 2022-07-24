@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.slide')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.slide')])</h1>
    {{ Breadcrumbs::render('lans.slides.edit', $lan, $slide) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('lans.slides.update', ['lan' => $lan, 'slide' => $slide])])
    @include('pages.slides.partials.form')
    @include('components.form.close')
@endsection
