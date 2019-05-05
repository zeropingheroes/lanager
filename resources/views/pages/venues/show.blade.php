@extends('layouts.default')

@section('title')
    {{ $venue->name }}
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>{{ $venue->name }}</h1>
        </div>
        @canany(['update', 'delete'], $venue)
            <div class="col text-right">
                @include('pages.venues.partials.actions-dropdown', ['venue' => $venue])
            </div>
        @endcanany
    </div>

    {{ Breadcrumbs::render('venues.show', $venue) }}
@endsection

@section('content')
    {!! Markdown::convertToHtml($venue->description) !!}

    <h4>@lang('title.street-address')</h4>
    <a href="https://www.google.co.uk/maps?q={{$venue->street_address}}" target="_blank">{{$venue->street_address}}</a>

    <h4>@lang('title.map')</h4>
    <iframe width="100%" height="500" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/search?q={{$venue->street_address}}&key={{ env('GOOGLE_API_KEY') }}"
            allowfullscreen></iframe>
@endsection