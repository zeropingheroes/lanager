@extends('layouts.default')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    @include('components.alerts.all')
    {!! Markdown::convertToHtml($page->content) !!}

    @if (!empty($page->children))
        <ul>
        @foreach($page->children as $child)
            <li>{{ link_to_route('pages.show',$child->title, $child->id) }}</li>
        @endforeach
        </ul>
    @endif

    @include('components.buttons.edit', ['item' => $page])
    @include('components.buttons.delete', ['item' => $page])

@endsection