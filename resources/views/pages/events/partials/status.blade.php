@php
    $start = (new \Carbon\Carbon($event->start));
    $end = (new \Carbon\Carbon($event->end));

    if ($start->isFuture() && $end->isFuture()) {
        $status = __('phrase.upcoming');
        $class = 'info';
    } elseif ($start->isPast() && $end->isFuture()) {
        $status = __('phrase.happening-now');
        $class = 'success';
    } elseif ($start->isPast() && $end->isPast()) {
        $status = __('phrase.ended');
        $class = 'danger';
    } else {
        $status = __('phrase.unknown');
        $class = 'secondary';
    }
@endphp

<span class="badge text-bg-{{ $class }}" title="@include('pages.events.partials.status-relative', ['event' => $event])">
    {{ $status }}
</span>
