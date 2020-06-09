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

    {!! Markdown::convertToHtml($event->description) !!}

    @if($event->signups_open && $event->signups_close)
        <h4>
            @lang('phrase.signups')
            @include('pages.events.partials.signups-status', ['event' => $event])
        </h4>
        @if(! $event->signups->isEmpty())
            @include('pages.events.partials.signups-list', ['event' => $event])
        @endif

        @if($event->signups_open->isPast() && $event->signups_close->isFuture())
            @can('create', [Zeropingheroes\Lanager\EventSignup::class, $event])
            @if(Auth::user()->eventSignups()->where('event_id', $event->id)->get()->isEmpty())
                @include('components.form.create', ['route' => route('lans.events.signups.store', ['lan' => $event->lan, 'event' => $event])])
                    <div class="form-group">
                    <button type="submit" class="btn btn-primary">@lang('title.sign-up')</button>
                    </div>
                @include('components.form.close')
            @endif
            @endcan
        @endif
    @endif

@endsection
