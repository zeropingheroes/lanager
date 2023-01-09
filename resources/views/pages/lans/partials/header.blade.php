<div class="row align-items-center">
    <div class="col-md-auto">
        <h1 class="mb-0">
            {{ $lan->name }}
        </h1>
    </div>
    @canany(['update', 'delete'], $lan)
        <div class="col text-right">
            @include('pages.lans.partials.actions-dropdown', ['lan' => $lan])
        </div>
    @endcanany
</div>
<h5>
    @if( $lan->venue )
        <a href="{{ route('venues.show', $lan->venue) }}">{{ $lan->venue->name }}</a>
    @endif
    <small class="text-muted">
        {{ $lan->start->format('H:i D j M Y') }} &ndash; {{ $lan->end->format('H:i D j M Y') }}
    </small>
</h5>
@include('pages.lans.partials.navigation-tabs', ['lan' => $lan])
