@extends('layouts.default')

@section('title')
    @lang('title.guides')
@endsection

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.guides')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Guide::class)
            <div class="col-auto text-right">
                <a href="{{ route( 'lans.guides.create', $lan) }}" class="btn btn-primary" title="@lang('title.create')"><span class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>

    {{ Breadcrumbs::render('lans.guides.index', $lan) }}

    @include('components.alerts.all')

    @include('pages.guides.partials.list', ['guides' => $guides])

    @can('create', Zeropingheroes\Lanager\Guide::class)
        <a href="{{ route( 'lans.guides.create', $lan->id) }}" class="btn btn-primary">@lang('title.create')</a>
    @endcan
@endsection