@extends('layouts.default')

@section('title')
    @lang('title.log') #{{ $log->id }}
@endsection

@section('content')

    <h1>@lang('title.log') #{{ $log->id }}</h1>

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