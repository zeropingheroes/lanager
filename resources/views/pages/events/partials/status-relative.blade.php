@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));
@endphp

@if($start->isFuture() && $end->isFuture())
    @lang('phrase.starting') {{ $start->diffForHumans() }}
@elseif($start->isPast() && $end->isFuture())
    @lang('phrase.ending') {{ $end->diffForHumans() }}
@elseif($start->isPast() && $end->isPast())
    @lang('phrase.ended') {{ $end->diffForHumans() }}
@else
    @lang('phrase.unknown')
@endif
