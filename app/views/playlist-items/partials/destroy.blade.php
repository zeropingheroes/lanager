<?php
	$confirmation = trans( 'confirmation.before.resource.destroy', ['resource' => 'playlist-item']);
	$route = ['playlists.items.destroy', $item->playlist_id, $item->id ];
?>

{{ Form::inline(['route' => $route, 'method' => 'DELETE', 'data-confirm' => $confirmation]) }}
	{{ Button::normal(Icon::trash())->extraSmall()->submit() }}
{{ Form::close() }}