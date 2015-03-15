@if( Authority::can('manage', 'playlists.items') )
	@include('buttons.url', 
	[
		'url' => $item->url,
		'icon' => 'newWindow',
		'size' => 'extraSmall',
		'hover' => 'Preview this item',
	])
@endif