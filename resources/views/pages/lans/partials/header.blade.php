<div class="row align-items-center">
    <div class="col-md-auto">
        <h1 class="mb-0">
            {{ $lan->name }}
            @canany(['update', 'delete'], $lan)
                @if(!$lan->published)
                    <span class="badge text-bg-secondary fs-6 align-middle"><i class="fa-solid fa-file-pen"></i> @lang('title.draft')</span>
                @endif
            @endcanany
        </h1>
    </div>
    @canany(['update', 'delete'], $lan)
        <div class="col text-end">
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
