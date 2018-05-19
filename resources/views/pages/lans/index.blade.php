@extends('layouts.default')

@section('title')
    @lang('title.lans')
@endsection

@section('content')
    <h1>@lang('title.lans')</h1>
    @include('components.alerts.all')

    @if( empty($lans))
        <p>@lang('phrase.no-items-found', ['item' => __('title.lans')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.name')</th>
                <th>@lang('title.dates')</th>
                <th>@lang('title.times')</th>
                <th>@lang('title.duration')</th>
                @if( Gate::allows('update', Zeropingheroes\Lanager\Lan::class) ||
                     Gate::allows('destroy', Zeropingheroes\Lanager\Lan::class) )
                    <th>
                        @lang('title.published')
                    </th>
                    <th>
                        @lang('title.actions')
                    </th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($lans as $lan)
                <tr>
                    <td>
                        {{ $lan->name }}
                    </td>
                    <td>
                        @include('pages.lans.partials.dates', ['lan' => $lan])
                    </td>
                    <td>
                        @include('pages.lans.partials.timespan', ['lan' => $lan])
                    </td>
                    <td>
                        @include('pages.lans.partials.duration', ['lan' => $lan])
                    </td>
                    @can('update', Zeropingheroes\Lanager\Lan::class)
                        <td>@include('components.tick-cross', ['value' => $lan->published])</td>
                    @endcan
                    @if( Gate::allows('update', $lan) || Gate::allows('destroy', $lan) )
                        <td>
                            @component('components.actions-dropdown')
                                @include('components.actions-dropdown.edit', ['item' => $lan])
                                @include('components.actions-dropdown.delete', ['item' => $lan])
                            @endcomponent
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\Lan::class])
    @endif
@endsection