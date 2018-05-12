@extends('layouts.default')

@section('title')
    @lang('title.navigation-links')
@endsection

@section('content')

    <h1>@lang('title.navigation-links')</h1>
    @include('components.alerts.all')

    @if( empty($navigationLinks))
        <p>@lang('phrase.no-items-found', ['item' => __('title.navigation-links')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.title')</th>
                <th>@lang('title.url')</th>
                <th>@lang('title.position')</th>
                <th>@lang('title.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($navigationLinks as $navigationLink)
                <tr>
                    <td>
                        {{ $navigationLink->title }}
                    </td>
                    <td>
                        <a href="{{ $navigationLink->url }}" target="_blank">{{ $navigationLink->url }}</a>
                    </td>
                    <td>
                        {{ $navigationLink->position }}
                    </td>
                    <td>
                        @component('components.actions-dropdown')
                            @include('components.actions-dropdown.edit', ['item' => $navigationLink])
                            @include('components.actions-dropdown.delete', ['item' => $navigationLink])
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\NavigationLink::class])
    @endif

@endsection