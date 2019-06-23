@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.achievement')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.achievement')])</h1>
    {{ Breadcrumbs::render('achievements.edit', $achievement) }}
@endsection

@section('content')
    <form method="POST" action="{{ route('achievements.update', $achievement) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {{method_field('PUT')}}
        @include('pages.achievements.partials.form')
    </form>
@endsection