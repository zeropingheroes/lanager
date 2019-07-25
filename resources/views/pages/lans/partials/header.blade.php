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
@if( $lan->venue )
    <h5><a href="{{ route('venues.show', $lan->venue) }}">{{ $lan->venue->name }}</a></h5>
@endif
@include('pages.lans.partials.navigation-tabs', ['lan' => $lan])
