@extends('layouts.default')

@section('title')
    @lang('title.achievements')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.achievements')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Achievement::class)
            <div class="col-auto text-right">
                <a href="{{ route('achievements.create') }}" class="btn btn-primary" title="@lang('title.create')"><span
                            class="oi oi-plus"></span></a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('achievements.index', $achievements) }}
@endsection

@section('content')
    @include('pages.achievements.partials.list', ['achievements' => $achievements])
@endsection
