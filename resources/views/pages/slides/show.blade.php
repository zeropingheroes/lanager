@extends('layouts.default')

@section('title')
    {{ $slide->name }}
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>{{ $slide->name }}</h1>
        </div>
        @canany(['update', 'delete'], $slide)
            <div class="col text-right">
                @include('pages.slides.partials.actions-dropdown', ['slide' => $slide])
            </div>
        @endcanany
    </div>

    {{ Breadcrumbs::render('lans.slides.show', $lan, $slide) }}
@endsection

@section('content-alerts')
    @parent
    @canany(['update', 'delete'], $slide)
    @if(!$slide->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.item-unpublished', ['item' => strtolower(__('title.slide'))])])
    @endif
    @endcanany
@endsection

@section('content')
    {!! Markdown::convertToHtml($slide->content) !!}
@endsection