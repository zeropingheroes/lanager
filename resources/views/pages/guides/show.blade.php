@extends('layouts.default')

@section('title')
    {{ $guide->title }}
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>{{ $guide->title }}</h1>
        </div>
        @canany(['update', 'delete'], $guide)
            <div class="col text-right">
                @include('pages.guides.partials.actions-dropdown', ['guide' => $guide])
            </div>
        @endcanany
    </div>

    {{ Breadcrumbs::render('lans.guides.show', $lan, $guide) }}
@endsection

@section('content-alerts')
    @parent
    @canany(['update', 'delete'], $guide)
    @if(!$guide->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.item-unpublished', ['item' => strtolower(__('title.guide'))])])
    @endif
    @endcanany
    @if($guide->lan->end->isPast())
        @include('components.alerts.alert-single', ['type' => 'danger', 'message' => __('phrase.viewing-guide-from-past-lan')])
    @endif
@endsection

@section('content')
    {!! Markdown::convertToHtml($guide->content) !!}
@endsection