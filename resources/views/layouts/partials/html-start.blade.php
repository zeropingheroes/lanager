<!doctype html>
<html lang="{{ app()->getLocale() }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ url('/api') }}">
    @if(Auth::user())
        <meta name="api-user-id" content="{{ Auth::user()->id }}">
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endif

    <title>@yield('title') | {{ config('app.name') }}</title>

    @vite([
        'resources/js/app.js',
        'resources/css/app.scss'
    ])

</head>
<body class="d-flex flex-column h-100">
