@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.info-page')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.info-page')])</h1>
    @include('components.alerts.all')
    {{ Form::model($page, ['route' => ['pages.update', $page->id], 'method' => 'put']) }}
    @include('pages.page.partials.form')
    {{ Form::close() }}
@endsection