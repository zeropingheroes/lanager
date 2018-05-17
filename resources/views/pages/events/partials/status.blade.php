@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));
@endphp

@if($start->isFuture() && $end->isFuture())
    @lang('phrase.upcoming')
@elseif($start->isPast() && $end->isFuture())
    @lang('phrase.happening-now')
@elseif($start->isPast() && $end->isPast())
    @lang('phrase.ended')
@else
    @lang('phrase.unknown')
@endif

{{-- TODO: implement coloured status label--}}
{{--<span class="label label-status-{{ $class }}" title="{{ $hover }}">{{ $status }}</span>--}}