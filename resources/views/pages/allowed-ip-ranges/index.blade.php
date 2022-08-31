@extends('layouts.default')

@section('title')
    @lang('title.allowed-ip-ranges')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.allowed-ip-ranges')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Models\AllowedIpRange::class)
            <div class="col-auto text-right">
                <a href="{{ route( 'allowed-ip-ranges.create') }}" class="btn btn-primary"
                   title="@lang('title.create')"><span class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('allowed-ip-ranges.index') }}
@endsection

@section('content')
    @include('pages.allowed-ip-ranges.partials.list', ['allowedIpRanges' => $allowedIpRanges])
@endsection
