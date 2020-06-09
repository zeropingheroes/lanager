@extends('layouts.default')

@section('title')
    @lang('title.guides')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @include('pages.guides.partials.list', ['guides' => $guides])
    @can('create', Zeropingheroes\Lanager\Guide::class)
        <a href="{{ route( 'lans.guides.create', $lan) }}" class="btn btn-primary">@lang('title.create')</a>
    @endcan
@endsection
