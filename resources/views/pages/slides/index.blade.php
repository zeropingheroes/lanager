@extends('layouts.default')

@section('title')
    @lang('title.slides')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.slides')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Slide::class)
            <div class="col-auto text-right">
                <a href="{{ route( 'lans.slides.create', ['lan' => $lan]) }}" class="btn btn-primary" title="@lang('title.create')"><span class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('lans.slides.index', $lan) }}
@endsection

@section('content')
    @include('pages.slides.partials.list', ['slides' => $slides])
@endsection