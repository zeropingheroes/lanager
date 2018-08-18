@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content-header')
    <h1>@lang('title.attendees')</h1>
    {{ Breadcrumbs::render('lans.attendees.index', $lan) }}
@endsection

@section('content')
    @include('pages.users.partials.list', ['users' => $users])
@endsection