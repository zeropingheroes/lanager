@extends('layouts.default')

@section('title')
    @lang('title.achievements')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.achievements')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Models\Achievement::class)
            <div class="col-auto text-end">
                <a href="{{ route('achievements.create') }}"
                   class="btn btn-primary"
                   title="@lang('title.create-item', ['item' => trans('title.achievement')])"
                >
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('achievements.index', $achievements) }}
@endsection

@section('content')
    @include('pages.achievements.partials.list', ['achievements' => $achievements])
@endsection
