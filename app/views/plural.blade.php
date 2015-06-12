@if ( $collection->count() > 0 )
	@if ( isset($hover) )
		<span data-toggle="tooltip" data-placement="right" title="{{{ implode( $hover, ', ') }}}">
	@endif
	{{ $collection->count() }} {{ str_plural($singular, $collection->count()) }}
	@if ( isset($hover) )
		</span>
	@endif
@endif