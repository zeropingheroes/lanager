@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.achievement')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.achievement')])</h1>
    {{ Breadcrumbs::render('achievements.edit', $achievement) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('achievements.update', $achievement)])
    @include('pages.achievements.partials.form')
    @include('components.form.close')
@endsection