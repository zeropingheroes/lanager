@if( Authority::can('manage', 'playlists') )
	{{ Form::open(array('route' => array('playlists.update', $playlist->id), 'method' => 'PUT', 'class' => 'form-inline')) }}
	@if( $playlist->playback_state == 1 )
		{{ Button::xs_submit('', array('title' => 'Pause playback', 'name' => 'playback_state', 'value' => 0))->with_icon('pause') }}
	@else 
		{{ Button::xs_submit('', array('title' => 'Resume playback', 'name' => 'playback_state', 'value' => 1))->with_icon('play') }}
	@endif
	{{ Form::close() }}
@endif