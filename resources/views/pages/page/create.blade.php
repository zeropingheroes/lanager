@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.info-page')])
@endsection

@section('content')
    <h1>@lang('title.create-item', ['item' => __('title.info-page')])</h1>
    @include('components.alerts')
    {{ Form::model(Zeropingheroes\Lanager\Page::class, ['route' => 'pages.store']) }}
    @include('pages.page.partials.form')
    {{ Form::close() }}
@endsection