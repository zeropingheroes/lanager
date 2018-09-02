@if($event->signups_open->isFuture() && $event->signups_close->isFuture())
    @lang('phrase.opening') {{ $event->signups_open->diffForHumans() }}
@elseif($event->signups_open->isPast() && $event->signups_close->isFuture())
    @lang('phrase.closing') {{ $event->signups_close->diffForHumans() }}
@elseif($event->signups_open->isPast() && $event->signups_close->isPast())
    @lang('phrase.closed') {{ $event->signups_close->diffForHumans() }}
@else
    @lang('phrase.unknown')
@endif
