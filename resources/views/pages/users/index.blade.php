@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @include('pages.users.partials.list', ['users' => $users])
@endsection