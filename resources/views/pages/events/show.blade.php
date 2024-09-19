@extends('layouts.default')

@section('title')
    {{ $event->name }}
@endsection

@section('content-header')
    @include('pages.events.partials.header-show', ['event' => $event])
    {{ Breadcrumbs::render('lans.events.show', $lan, $event) }}
@endsection

@section('content')
    @canany(['update', 'delete'], $event)
        @if(!$event->published)
            @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.item-unpublished', ['item' => strtolower(__('title.event'))])])
        @endif
    @endcanany
    @canany(['update', 'delete'], $event->lan)
        @if(!$event->lan->published)
            @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.item-unpublished', ['item' => __('title.lan')])])
        @endif
    @endcanany
    @canany(['update', 'delete'], $event)
        <div class="float-end">
            @include('pages.events.partials.actions-dropdown', ['event' => $event])
        </div>
    @endcanany
    {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( (string) $event->description) !!}
    @if($event->signups_open && $event->signups_close)
        <hr>
        <div class="row align-items-center">
            <div class="col-md-auto">
                <h4>@lang('phrase.signups')</h4>
            </div>
            <div class="col text-end">
                <h4>@include('pages.events.partials.signups-status', ['event' => $event])</h4>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-auto">
                <h6 class="text-muted">
                    @lang('phrase.open') @include('pages.events.partials.terse-timespan', ['start' => $event->signups_open, 'end' => $event->signups_close])
                </h6>
            </div>
            <div class="col text-end">
                @include('pages.events.partials.signups-status-relative', ['event' => $event])
            </div>
        </div>
        @if(! $event->signups->isEmpty())
            @include('pages.events.partials.signups-list', ['event' => $event])
        @endif

        @if($event->signups_open->isPast() && $event->signups_close->isFuture())
            @can('create', [Zeropingheroes\Lanager\Models\EventSignup::class, $event])
                @if(Auth::user()->eventSignups()->where('event_id', $event->id)->get()->isEmpty())
                    @include('components.form.create', ['route' => route('lans.events.signups.store', ['lan' => $event->lan, 'event' => $event])])
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">@lang('title.sign-up')</button>
                    </div>
                    @include('components.form.close')
                @endif
            @endcan
        @endif
    @endif

@endsection
