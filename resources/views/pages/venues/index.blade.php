@extends('layouts.default')

@section('title')
    @lang('title.venues')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.venues')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Venue::class)
            <div class="col-auto text-right">
                <a href="{{ route( 'venues.create') }}" class="btn btn-primary" title="@lang('title.create')"><span class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('venues.index') }}
@endsection

@section('content')
    @include('pages.venues.partials.list', ['venues' => $venues])
@endsection