@extends('layouts.default')

@section('title')
    @lang('title.lans')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-auto">
            <h1>@lang('title.lans')</h1>
        </div>
        @can('create', \Zeropingheroes\Lanager\Models\Lan::class)
            <div class="col text-right">
                <a href="{{ route( 'lans.create') }}"
                   class="btn btn-primary"
                   title="@lang('title.create-item', ['item' => trans('title.lan')])"
                   id="create-lan-button"
                >
                    <span class="oi oi-plus"></span>
                </a>
            </div>
        @endcan
    </div>
    {{ Breadcrumbs::render('lans.index') }}
@endsection

@section('content')
    @if( empty($lans))
        <p>@lang('phrase.no-items-found', ['item' => __('title.lans')])</p>
    @else
        <table class="table table-striped">
            <tbody>
            @foreach($lans as $lan)
                @can('view', $lan)
                    <tr @if($currentLan && $lan->id == $currentLan->id) class="table-active" @endif>
                        <td>
                            <a href="{{ route('lans.show', $lan->id) }}">{{ $lan->name }}</a>
                        </td>
                        <td>
                            {{ $lan->start->format('H:i D j M Y') }} &ndash; {{ $lan->end->format('H:i D j M Y') }}
                        </td>
                        <td>
                            @lang('title.x-hours', ['x' => $lan->start->diffInHours($lan->end)])
                        </td>
                        <td>
                            {{ $lan->users->count() }} <span class="oi oi-person" title="attendee"
                                                             aria-hidden="true"></span>
                        </td>
                        @canany(['edit', 'delete'], $lan)
                            <td class="text-right pr-0">
                                @component('components.actions-dropdown')
                                    @include('components.actions-dropdown.edit', ['item' => $lan])
                                    @include('components.actions-dropdown.delete', ['item' => $lan])
                                @endcomponent
                            </td>
                        @endcanany
                    </tr>
                @endcan
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
