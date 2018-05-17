@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));

    if ($start->isFuture() && $end->isFuture()) {
        $status = __('phrase.upcoming');
    } elseif ($start->isPast() && $end->isFuture()) {
        $status = __('phrase.happening-now');
    } elseif ($start->isPast() && $end->isPast()) {
        $status = __('phrase.ended');
    } else {
        $status = __('phrase.unknown');
    }
@endphp

<span class="badge badge-secondary" title="@include('pages.events.partials.status-relative', ['event' => $event])">
    {{ $status }}
</span>
