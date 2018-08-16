@extends('layouts.default')

@section('title')
    @lang('title.lans')
@endsection

@section('content')
    <h1>@lang('title.lans')</h1>
    {{ Breadcrumbs::render('lans.index') }}
    @include('components.alerts.all')

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
                            @include('pages.lans.partials.dates', ['lan' => $lan])
                        </td>
                        <td>
                            @include('pages.lans.partials.timespan', ['lan' => $lan])
                            <small class="text-muted">
                                @include('pages.lans.partials.duration', ['lan' => $lan])
                            </small>
                        </td>
                        <td>
                            {{ $lan->users->count() }} <span class="oi oi-person" title="attendee" aria-hidden="true"></span>
                        </td>
                        @canany(['edit', 'delete'], $lan)
                            <td>
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
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\Lan::class])
    @endif
@endsection