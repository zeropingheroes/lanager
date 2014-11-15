@if( Authority::can('manage', 'playlists') )
	{{ Form::open(array('route' => array('playlists.update', $playlist->id), 'method' => 'PUT', 'class' => 'form-inline')) }}
	@if( $playlist->playback_state == 1 )
		{{ Button::normal('', array('title' => 'Pause playback', 'name' => 'playback_state', 'value' => 0))->withIcon(Icon::pause()) }}
	@else 
		{{ Button::normal('', array('title' => 'Resume playback', 'name' => 'playback_state', 'value' => 1))->withIcon(Icon::play()) }}
	@endif
	{{ Form::close() }}
@endif
