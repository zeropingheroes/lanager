@include(
	'buttons.update',
	[
		'resource' => 'playlists.items',
		'icon' => 'stepForward',
		'hover' => 'Skip this playlist item immediately',
		'size' => 'extraSmall',
		'id' => 'skip'.$item->id,
		'data' => 
		[
			'playback_state' => 2,
		],
		'parameters' =>
		[
			'playlist_id' => $item->playlist_id,
			'item_id' => $item->id,
		],
	])
@if( Authority::can('update', 'playlists.items') )
	<script type="text/javascript">
		$( "#skip{{ $item->id }}" ).submit(function( event ) {
			var skip_reason = prompt("Please enter a brief reason for skipping this item");
			if( skip_reason )
			{
				var input = $("<input>")
				.attr("type", "hidden")
				.attr("name", "skip_reason").val(skip_reason);
				$( "#skip{{ $item->id }}" ).append($(input));
			}
			else
			{
				event.preventDefault();
			}
		});
	</script>
@endif