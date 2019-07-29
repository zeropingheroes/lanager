@extends('layouts.default')

@section('title')
    @lang('title.slides')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @include('pages.slides.partials.list', ['slides' => $slides])
    @can('create', \Zeropingheroes\Lanager\Slide::class)
        <a href="{{ route( 'lans.slides.play', $lan) }}" class="btn btn-primary" title="@lang('title.play')" target="_blank">
            <span class="oi oi-media-play"></span>
        </a>
        <a href="{{ route( 'lans.slides.create', ['lan' => $lan]) }}" class="btn btn-primary" title="@lang('title.create')">
            <span class="oi oi-plus"></span>
        </a>
    @endcan
@endsection