<div class="row align-items-center">
    <div class="col-md-auto">
        <h1>{{ $event->name }}</h1>
    </div>
    <div class="col text-end">
        <h2>
            @include('pages.events.partials.status', ['event' => $event])
        </h2>
    </div>
</div>
<div class="row align-items-center">
    <div class="col-md-auto">
        <h4 class="text-muted">
            @include('pages.events.partials.terse-timespan', ['start' => $event->start, 'end' => $event->end])
        </h4>
    </div>
    <div class="col text-end">
        <span class="h5">@include('pages.events.partials.status-relative')</span>
    </div>
</div>
