@extends('layouts.default')

@section('title')
    @lang('title.pages')
@endsection

@section('content')
    <h1>@lang('title.pages')</h1>
    @include('components.alerts')

    @include('pages.page.partials.list', ['pages' => $pages])
@endsection