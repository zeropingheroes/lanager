@if( $collection->count() > 0 )
	{{ $collection->count() }} {{ str_plural($singular, $collection->count()) }}
@endif