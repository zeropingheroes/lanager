@extends('layouts.default')

@section('title')
    @lang('title.event-types')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1>@lang('title.event-types')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\EventType::class)
            <div class="col text-right">
                <a href="{{ route( 'event-types.create') }}" class="btn btn-primary" title="@lang('title.create')">
                    <span class="oi oi-plus"></span>
                </a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('event-types.index') }}
@endsection

@section('content')
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
@endsection