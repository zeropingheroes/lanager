@if( Authority::can('create', 'playlists.items') )
	{{ Form::horizontal(array('route' => array('playlists.items.store', $playlist->id), 'class' => 'form-inline playlist-item-create')) }}
		{{ 
			InputGroup::withContents(
				Form::text('url', NULL, ['placeholder' => 'Paste a YouTube video URL'] )
			)->appendButton(Button::normal('Post')->submit())
		}}
	{{ Form::close() }}
@endif
