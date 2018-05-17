<div class="row">
    <div class="col-md-8">
        <h4>
            @include('pages.events.partials.start-and-end', ['event' => $event])
            <small class="text-muted">
                @include('pages.events.partials.status-relative', ['event' => $event])
            </small>
        </h4>
    </div>
    {{-- TODO: signups status display --}}
    {{--<div class="col-md-4">--}}
        {{--<h4 class="pull-right">--}}
            {{--@if ( ! empty( $event->signup_opens) )--}}
                {{--@include('pages.events.partials.signups-status-relative', ['event' => $event])--}}
            {{--@endif--}}
        {{--</h4>--}}
    {{--</div>--}}
</div>