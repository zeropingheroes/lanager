@if( Authority::can('create', 'shouts') )
	{{ Form::horizontal(array('route' => 'shouts.store', 'class' => 'form-inline shout-create')) }}
		{{
			InputGroup::withContents(
				Form::text('content', NULL, array('placeholder' => 'What\'s going on?', 'maxlength' => 140) )
			)->appendButton(Button::normal('Post')->submit())
         }}
	{{ Form::close() }}
@endif
