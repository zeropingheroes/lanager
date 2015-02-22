<?php
	$route = ['playlists.items.votes.store', $item->playlist_id, $item->id ];
?>

{{ Form::inline(['route' => $route, 'method' => 'POST']) }}
	{{ Button::normal(Icon::stepForward())->extraSmall()->submit() }}
{{ Form::close() }}