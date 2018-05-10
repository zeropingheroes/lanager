@extends('layouts.default')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    @include('components.alerts')
    {!! Markdown::convertToHtml($page->content) !!}

    @if (!empty($page->children))
        <ul>
        @foreach($page->children as $child)
            <li>{{ link_to_route('pages.show',$child->title, $child->id) }}</li>
        @endforeach
        </ul>
    @endif

    @can('update', $page)
        @include('components.edit', ['route' => route('pages.edit', $page->id)])
    @endcan
    @can('delete', $page)
        @include('components.delete', ['route' => route('pages.destroy', $page->id)])
    @endcan
@endsection