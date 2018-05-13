@extends('layouts.default')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    @include('components.alerts.all')

    {!! Markdown::convertToHtml($page->content) !!}

    @include('components.buttons.edit', ['item' => $page])
    @include('components.buttons.delete', ['item' => $page])

@endsection