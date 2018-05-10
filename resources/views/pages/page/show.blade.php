@extends('layouts.default')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    @include('components.alerts')
    {!! Markdown::convertToHtml($page->content) !!}
    @can('update', $page)
        @include('components.edit', ['route' => route('pages.edit', $page->id)])
    @endcan
    @can('delete', $page)
        @include('components.delete', ['route' => route('pages.destroy', $page->id)])
    @endcan
@endsection