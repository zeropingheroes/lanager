@php
    if ($event->signups_open->isFuture() && $event->signups_close->isFuture()) {
        $status = __('phrase.not-yet-open');
        $class = 'future';
    } elseif ($event->signups_open->isPast() && $event->signups_close->isFuture()) {
        $status = __('phrase.open');
        $class = 'present';
    } elseif ($event->signups_open->isPast() && $event->signups_close->isPast()) {
        $status = __('phrase.closed');
        $class = 'past';
    } else {
        $status = __('phrase.unknown');
        $class = 'secondary';
    }
@endphp

<span class="badge badge-{{ $class }}" title="@include('pages.events.partials.signups-status-relative', ['event' => $event])">
    {{ $status }}
</span>
