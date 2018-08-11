@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.guide')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.guide')])</h1>
    {{ Breadcrumbs::render('lans.guides.edit', $guide->lan, $guide) }}
    @include('components.alerts.all')
    @include('components.form.edit', ['route' => route('lans.guides.update', ['lan' => $guide->lan, 'guide' => $guide->id])])
    @include('pages.guides.partials.form')
    @include('components.form.close')
@endsection