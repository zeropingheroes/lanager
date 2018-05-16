@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    <h1>@lang('title.events')</h1>
    @include('components.alerts.all')

    @if( empty($events))
        <p>@lang('phrase.no-items-found', ['item' => __('title.events')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.name')</th>
                <th>@lang('title.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($events as $event)
                <tr>
                    <td>
                        {{ $event->name }}
                    </td>
                    <td>
                        @component('components.actions-dropdown')
                            @include('components.actions-dropdown.edit', ['item' => $event])
                            @include('components.actions-dropdown.delete', ['item' => $event])
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\Event::class])
    @endif
@endsection