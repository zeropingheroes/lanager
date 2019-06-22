<div class="row align-items-center">
    <div class="col-md-auto">
        <h1>{{ $event->name }}</h1>
    </div>
    <div class="col text-right">
        <h2>
            @include('pages.events.partials.status', ['event' => $event])
        </h2>
    </div>
</div>
<div class="row align-items-center">
    <div class="col-md-auto">
        <h4 class="text-muted">
            @include('pages.events.partials.start-and-end', ['event' => $event])
        </h4>
    </div>
    @canany(['update', 'delete'], $event)
        <div class="col text-right">
            @include('pages.events.partials.actions-dropdown', ['event' => $event])
        </div>
    @endcanany
</div>