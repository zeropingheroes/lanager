{{ Form::inline( ['route' => ['events.signups.store', $event->id ] ]) }}
	{{ Form::hidden( 'user_id', Auth::user()->id )}}
	{{ Button::normal('Sign up')->submit() }}
{{ Form::close() }}

