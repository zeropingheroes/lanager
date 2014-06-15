@if( Authority::can('create', 'shouts') )
	{{ Form::open(array('route' => 'shouts.store', 'class' => 'form-inline shout-create')) }}
		{{ Form::text('content', NULL, array('placeholder' => 'What\'s going on?', 'maxlength' => 140) ) }}
		{{ Button::inverse_submit('Post') }}
	{{ Form::close() }}
@endif
