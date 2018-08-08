@extends('layouts.default')

@section('title')
    @lang('title.pages')
@endsection

@section('content')
    <h1>@lang('title.pages')</h1>
    {{ Breadcrumbs::render('lans.pages.index', $lan) }}

    @include('components.alerts.all')

    @if( empty($pages))
        <p>@lang('phrase.no-items-found', ['item' => __('title.pages')])</p>
    @else
        @include('pages.pages.partials.list', ['pages' => $pages])
        @can('create', Zeropingheroes\Lanager\Page::class)
            <a href="{{ route( 'lans.pages.create', $lan->id) }}" class="btn btn-primary">@lang('title.create')</a>
        @endcan
    @endif
@endsection