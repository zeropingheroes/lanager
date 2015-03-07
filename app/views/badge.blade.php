@if( $collection->count() ) 
	&nbsp; {{ Badge::withContents( $collection->count() ) }}
@endif
