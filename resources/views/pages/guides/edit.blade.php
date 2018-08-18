@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.guide')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.guide')])</h1>
    {{ Breadcrumbs::render('lans.guides.edit', $guide->lan, $guide) }}
@endsection

@section('content-body')
    @include('components.form.edit', ['route' => route('lans.guides.update', ['lan' => $guide->lan, 'guide' => $guide->id])])
    @include('pages.guides.partials.form')
    @include('components.form.close')
@endsection