@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content')

    <h1>@lang('title.attendees')</h1>

    {{ Breadcrumbs::render('lans.attendees.index', $lan) }}

    @include('pages.users.partials.list', ['users' => $users])
@endsection