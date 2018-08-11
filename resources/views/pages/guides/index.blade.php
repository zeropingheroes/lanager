@extends('layouts.default')

@section('title')
    @lang('title.guides')
@endsection

@section('content')
    <h1>@lang('title.guides')</h1>
    {{ Breadcrumbs::render('lans.guides.index', $lan) }}

    @include('components.alerts.all')

    @if( empty($guides))
        <p>@lang('phrase.no-items-found', ['item' => __('title.guides')])</p>
    @else
        @include('pages.guides.partials.list', ['guides' => $guides])
        @can('create', Zeropingheroes\Lanager\Guide::class)
            <a href="{{ route( 'lans.guides.create', $lan->id) }}" class="btn btn-primary">@lang('title.create')</a>
        @endcan
    @endif
@endsection