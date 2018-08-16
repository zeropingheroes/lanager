@extends('layouts.default')

@section('title')
    @lang('title.event-types')
@endsection

@section('content')
    <h1>@lang('title.event-types')</h1>
    @include('components.alerts.all')

    <table class="table table-striped">
        <tbody>
        @foreach($eventTypes as $eventType)
            @can('view', $eventType)
                <tr>
                    <td>
                        {{ $eventType->name }}
                    </td>
                    <td style="color: {{ $eventType->colour }}">
                        <span class="oi oi-calendar" aria-hidden="true"></span> {{ strtoupper($eventType->colour) }}
                    </td>
                    @canany(['edit', 'delete'], $eventType)
                        <td>
                            @component('components.actions-dropdown')
                                @include('components.actions-dropdown.edit', ['item' => $eventType])
                                @include('components.actions-dropdown.delete', ['item' => $eventType])
                            @endcomponent
                        </td>
                    @endcanany
                </tr>
            @endcan
        @endforeach
        </tbody>
    </table>
    @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\EventType::class])
@endsection