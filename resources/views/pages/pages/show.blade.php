@extends('layouts.default')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <h1>{{ $page->title }}</h1>
    @include('components.alerts.all')

    @if(!$page->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.page-not-published')])
    @endif

    @if($page->lan_id != $lan->id)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.viewing-page-from-past-lan')])
    @endif

    {!! Markdown::convertToHtml($page->content) !!}

    @include('components.buttons.edit', ['item' => $page])
    @include('components.buttons.delete', ['item' => $page])

@endsection