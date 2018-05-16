@extends('layouts.default')

@section('title')
    @lang('title.event-types')
@endsection

@section('content')
    <h1>@lang('title.event-types')</h1>
    @include('components.alerts.all')

    @if( empty($eventTypes))
        <p>@lang('phrase.no-items-found', ['item' => __('title.event-types')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.name')</th>
                <th>@lang('title.colour')</th>
                <th>@lang('title.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($eventTypes as $eventType)
                <tr>
                    <td>
                        {{ $eventType->name }}
                    </td>
                    <td style="color: {{ $eventType->colour }}">
                        <span class="oi oi-calendar" aria-hidden="true"></span> {{ strtoupper($eventType->colour) }}
                    </td>
                    <td>
                        @component('components.actions-dropdown')
                            @include('components.actions-dropdown.edit', ['item' => $eventType])
                            @include('components.actions-dropdown.delete', ['item' => $eventType])
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\EventType::class])
    @endif
@endsection