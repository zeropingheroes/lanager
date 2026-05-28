@extends('layouts.default')

@section('title')
    {{ $achievement->name }}
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>
                @if($achievement->image_filename)
                    <img src="/storage/images/achievements/{{ $achievement->image_filename }}" height="64px" width="64px">
                @endif
                {{ $achievement->name }}
            </h1>
        </div>
        @canany(['edit', 'delete'], $achievement)
            <div class="col text-end">
                @component('components.actions-dropdown')
                    @include('components.actions-dropdown.edit', ['item' => $achievement])
                    @include('components.actions-dropdown.delete', ['item' => $achievement])
                @endcomponent
            </div>
        @endcanany
    </div>

    {{ Breadcrumbs::render('achievements.show', $achievement) }}
@endsection

@section('content')
    @if($achievement->description)
        {!! GrahamCampbell\Markdown\Facades\Markdown::convert( (string) $achievement->description) !!}
    @endif

@endsection
