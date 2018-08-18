<div class="row align-items-center">
    <div class="col-md-auto">
        <h1>{{ $event->name }}</h1>
    </div>
    <div class="col text-right">
        <h2>@include('pages.events.partials.status', ['event' => $event])</h2>
    </div>
</div>
<div class="row">
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
    {{-- TODO: signups status display --}}
    {{--<div class="col-md-4">--}}
    {{--<h4 class="pull-right">--}}
    {{--@if ( ! empty( $event->signup_opens) )--}}
    {{--@include('pages.events.partials.signups-status-relative', ['event' => $event])--}}
    {{--@endif--}}
    {{--</h4>--}}
    {{--</div>--}}
</div>