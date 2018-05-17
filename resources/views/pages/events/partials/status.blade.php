@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));

    if ($start->isFuture() && $end->isFuture()) {
        $status = __('phrase.upcoming');
        $class = 'future';
    } elseif ($start->isPast() && $end->isFuture()) {
        $status = __('phrase.happening-now');
        $class = 'present';
    } elseif ($start->isPast() && $end->isPast()) {
        $status = __('phrase.ended');
        $class = 'past';
    } else {
        $status = __('phrase.unknown');
        $class = 'secondary';
    }
@endphp

<span class="badge badge-{{ $class }}" title="@include('pages.events.partials.status-relative', ['event' => $event])">
    {{ $status }}
</span>
