@if( Authority::can('create', 'playlistItems') )
	{{ Form::open(array('route' => array('playlists.items.store', $playlist->id), 'class' => 'form-inline playlist-item-create')) }}
	{{ HTML::validationErrors() }}

	{{ Form::text('url', NULL, array('placeholder' => 'Paste a YouTube video URL') ) }}
	{{ Button::inverse_submit('Submit') }}
	{{ Form::close() }}
@endif