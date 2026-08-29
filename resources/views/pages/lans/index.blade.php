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
            <div class="col text-end">
                <a href="{{ route( 'lans.create') }}"
                   class="btn btn-primary"
                   title="@lang('title.create-item', ['item' => trans('title.lan')])"
                   id="create-lan-button"
                >
                    <i class="fa-solid fa-plus"></i>
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
                            @canany(['update', 'delete'], $lan)
                                @if(!$lan->published)
                                    <small>&ndash; @lang('title.unpublished')</small>
                                @endif
                            @endcanany
                        </td>
                        <td>
                            {{ $lan->start->format('M Y') }}
                        </td>
                        <td>
                            @if($lan->venue)
                                <a href="{{ route('venues.show', $lan->venue->id) }}">{{ $lan->venue->name }}</a>
                            @endif
                        </td>
                        <td>
                            @lang('title.x-hours', ['x' => (int) $lan->start->diffInHours($lan->end)])
                        </td>
                        <td>
                            {{ $lan->users->count() }} <i class="fa-solid fa-user"></i>
                        </td>
                        @canany(['edit', 'delete'], $lan)
                            <td class="text-end pe-0">
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
