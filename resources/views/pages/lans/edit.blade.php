@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.lan')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.lan')])</h1>
    {{ Breadcrumbs::render('lans.edit', $lan) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('lans.update', $lan->id)])
    @include('pages.lans.partials.form')
    @include('components.form.close')
@endsection