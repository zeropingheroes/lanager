@extends('layouts.default')

@section('title')
    {{ $guide->title }}
@endsection

@section('content')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>{{ $guide->title }}</h1>
        </div>
        <div class="col text-right">
            @include('pages.guides.partials.actions-dropdown', ['guide' => $guide])
        </div>
    </div>


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

@endsection