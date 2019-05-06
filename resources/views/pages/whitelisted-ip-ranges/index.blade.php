@extends('layouts.default')

@section('title')
    @lang('title.whitelisted-ip-ranges')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.whitelisted-ip-ranges')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\WhitelistedIpRange::class)
            <div class="col-auto text-right">
                <a href="{{ route( 'whitelisted-ip-ranges.create') }}" class="btn btn-primary" title="@lang('title.create')"><span class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('whitelisted-ip-ranges.index') }}
@endsection

@section('content')
    @include('pages.whitelisted-ip-ranges.partials.list', ['whitelistedIpRanges' => $whitelistedIpRanges])
@endsection