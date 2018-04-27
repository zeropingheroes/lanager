@extends('layouts.default')

@section('title')
    @lang('title.log') #{{ $log->id }}
@endsection

@section('content')

    <h1>@lang('title.log') #{{ $log->id }} @include('pages.log.partials.level', ['level' => $log->level_name])</h1>

    <h2>@lang('title.message')</h2>
    <code>{{ $log->message }}</code>

    <h2>@lang('title.time')</h2>
    <code>@include('components.time-datetime', ['datetime' => $log->created_at])</code>

    <h2>@lang('title.data')</h2>
    <code>
        <pre>{{ var_export_short([
    'level' => $log->level_name,
    'message' => $log->message,
    'time' => (string) $log->created_at,
    'user' => $log->user->username,
    'context' => json_decode($log->context,true)
]) }}</pre>
    </code>

@endsection