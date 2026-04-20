@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));
@endphp

@if($start->isFuture() && $end->isFuture())
    @lang('phrase.starting-in-x', ['x' => $start->longAbsoluteDiffForHumans()])
@elseif($start->isPast() && $end->isFuture())
    @lang('phrase.ending-in-x', ['x' => $end->longAbsoluteDiffForHumans()])
@elseif($start->isPast() && $end->isPast())
    @lang('phrase.ended-x-ago', ['x' => $end->longAbsoluteDiffForHumans()])
@else
    @lang('phrase.unknown')
@endif
