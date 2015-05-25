@if( isset(Auth::user()->id) )
	@if( ! $event->hasSignupFromUser(Auth::user()->id) AND $event->isOpenForSignups() )
		@include(
			'buttons.store',
			[
				'resource' => 'events.signups',
				'text' => 'Sign up',
				'size' => 'normal',
				'icon' => 'plus',
				'hover' => 'Sign up to this event',
				'parameters' => ['event_id' => $event->id],
				'data' => ['event_id' => $event->id, 'user_id' => Auth::user()->id],
			])
	@endif
@endif