@extends('layouts.default')

@section('title')
    @lang('http-status-codes.'.$code.'-title')
@endsection

@section('content')
    <h1>@lang('http-status-codes.'.$code.'-title')</h1>
    <p>@lang('http-status-codes.'.$code.'-description')</p>
@endsection
