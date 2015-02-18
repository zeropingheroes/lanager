@if(count($eventSignups))
	<?php
	foreach( $eventSignups as $eventSignup )
	{
		$user = $eventSignup->user;
		$tableBody[] = array(
			'user'		=> View::make('users.partials.avatar-username', ['user' => $user]),
			'controls'	=> Button::normal('Remove Signup')
							->asLinkTo( route('events.signups.destroy', ['event' => $eventSignup->event->id, 'signup' => $eventSignup->id]) )
							->prependIcon(Icon::remove())
							->withAttributes( ['data-method' => 'DELETE', 'data-confirm' => 'Are you sure you want to destroy this event signup?'] ),
		);
	}
	?>
	{{ Table::withContents($tableBody) }}
@else
	No event signups!
@endif
