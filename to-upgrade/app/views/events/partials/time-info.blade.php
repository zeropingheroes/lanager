<div class="row">
    <div class="col-md-8">
        <h4>
            {{ $event->present()->timespan }}
            <small>{{ $event->present()->timespanRelativeToNow }}</small>
        </h4>
    </div>
    <div class="col-md-4">
        <h4 class="pull-right">
            @if ( ! empty( $event->signup_opens) )
                {{ $event->present()->signupTimespanStatusLabel('Signups ') }}
            @endif
        </h4>
    </div>
</div>