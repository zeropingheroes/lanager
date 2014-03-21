@if( Authority::can('create', 'playlistItem') )
	{{ Form::open(array('route' => array('playlist.item.store', $playlist->id), 'class' => 'form-inline playlist-item-create')) }}
	{{ HTML::validationErrors() }}

	{{ Form::text('url', NULL, array('placeholder' => 'Paste a YouTube video URL') ) }}
	{{ Button::inverse_submit('Submit') }}
	{{ Form::close() }}
@endif