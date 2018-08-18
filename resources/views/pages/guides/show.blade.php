@extends('layouts.default')

@section('title')
    {{ $guide->title }}
@endsection

@section('content')
    <h1>{{ $guide->title }}</h1>
    {{ Breadcrumbs::render('lans.guides.show', $lan, $guide) }}

    @include('components.alerts.all')

    @canany(['update', 'delete'], $guide)
    @if(!$guide->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.resource-not-published', ['resource' => strtolower(__('title.guide'))])])
    @endif
    @endcanany


    @if($guide->lan->end->isPast())
        @include('components.alerts.alert-single', ['type' => 'danger', 'message' => __('phrase.viewing-guide-from-past-lan')])
    @endif

    {!! Markdown::convertToHtml($guide->content) !!}

    @can('update', $guide)
        <a href="{{ route( 'lans.guides.edit', ['lan' => $lan, 'guide' => $guide]) }}" class="btn btn-primary">@lang('title.edit')</a>
    @endcan
    @can('delete', $guide)
        <form action="{{ route( 'lans.guides.destroy', ['lan' => $lan, 'guide' => $guide]) }}" method="POST" class="inline">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger">@lang('title.delete')</button>
        </form>
    @endcan

@endsection