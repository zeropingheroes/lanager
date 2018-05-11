@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.info-page')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.info-page')])</h1>
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('pages.update', $page->id)])
    @include('pages.page.partials.form')
    @include('components.form.close')
@endsection