<div class="row">
    <div class="col-md-8">
        <h1 class="pull-left">
            {{ $event->name }}
                <small class="text-muted">
                    {{ $event->type->name }}
                </small>
        </h1>
    </div>
    <div class="col-md-4">
        <h1 class="pull-right">
            @include('pages.events.partials.status', ['event' => $event])
        </h1>
    </div>
</div>
