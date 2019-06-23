@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.achievement')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.achievement')])</h1>
    {{ Breadcrumbs::render('achievements.create') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('achievements.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @include('pages.achievements.partials.form')
    </form>
@endsection