@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.info-page')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.info-page')])</h1>
    {{ Breadcrumbs::render('lans.pages.edit', $page->lan, $page) }}
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('lans.pages.update', ['lan' => $page->lan, 'page' => $page->id])])
    @include('pages.pages.partials.form')
    @include('components.form.close')
@endsection